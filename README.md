
# Laravel 入门博客项目
注：本项目基于 Laravel 8.6 开发，本地环境是 Windows10 + Laragon。

## 创建博客项目

### 改为阿里云composer镜像
composer config -g repo.packagist composer https://mirrors.aliyun.com/composer/
### 通过 Composer 安装一个全新的 Laravel 项目 blog：
composer create-project laravel/laravel blog --prefer-dist   
### 在数据库中新增一个名为 blog 的数据库
### 修改根目录下的 .env 文件的数据库配置信息
### 改为淘宝npm镜像
npm config set registry https://registry.npm.taobao.org
### 在根目录下初始化前端资源：
npm install


## 初步搭建博客系统

### 创建文章数据表及模型
#### 创建一个新的文章表 posts 及该表对应的模型类 Post： 
php artisan make:model -m Post
上述命令会做两件事情：
在 app/Models 目录下创建模型类 Post；
创建 posts 表的数据库迁移，该迁移文件位于 database/migrations 目录下。
#### 编辑 database/migrations 目录下刚生成的这个迁移文件，内容如下：
    <?php
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;
    class CreatePostsTable extends Migration
    {
        public function up()
        {
            Schema::create('posts', function (Blueprint $table) {
                $table->id();
                $table->string('slug')->unique();
                $table->string('title');
                $table->text('content');
                $table->softDeletes();
                $table->timestamp('published_at')->nullable();            
                $table->timestamps();
            });
        }
        public function down()
        {
            Schema::dropIfExists('posts');
        }
    }
我们在默认生成的迁移文件基础上新增五个额外的列：
slug：将文章标题转化为 URL 的一部分，以利于SEO
title：文章标题
content：文章内容
published_at：文章正式发布时间
deleted_at：用于支持软删除
#### 数据迁移： 
    php artisan migrate
#### 修改生成的默认 app/Models/Post.php 文件内容如下：
    <?php
    namespace App\Models;
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Support\Str;
    class Post extends Model
    {
        use HasFactory;
        protected $dates = ['published_at'];
        public function setTitleAttribute($value)
        {
            $this->attributes['title'] = $value;
            if (! $this->exists) {
                $this->attributes['slug'] = Str::slug($value);
            }
        }
    }



### 使用测试数据填充文章表
填充一些初始化数据到数据表 posts 中。这里我们要用到 Laravel 的模型工厂功能。

#### 创建一个模型工厂文件：
php artisan make:factory PostFactory --model=Post
#### 添加如下代码到 database/factories 目录下的 PostFactory.php 文件中：
    <?php
    namespace Database\Factories;
    use Illuminate\Database\Eloquent\Factories\Factory;
    class PostFactory extends Factory
    {
        /**
        * Define the model's default state.
        *
        * @return array
        */
        public function definition()
        {
            return [
                'title' => $this->faker->sentence(mt_rand(3, 10)),
                'content' => join("\n\n", $this->faker->paragraphs(mt_rand(3, 6))),
                'published_at' => $this->faker->dateTimeBetween('-1 month', '+3 days'),
            ];
        }
    }
#### 运行如下 Artisan 命令创建一个新的填充类文件： 
php artisan make:seeder PostsTableSeeder
#### 编写在 database/seeds 目录下新生成的 PostsTableSeeder 类文件如下：
    <?php
    namespace Database\Seeders;
    use Illuminate\Database\Seeder;
    use App\Models\Post;
    class PostsTableSeeder extends Seeder
    {
        /**
        * Run the database seeds.
        *
        * @return void
        */
        public function run()
        {
            Post::truncate();  // 先清理表数据
            Post::factory(20)->create();  // 一次填充20篇文章
        }
    }
#### 修改 database/seeds 目录下的 DatabaseSeeder.php 内容如下： 
    <?php
    namespace Database\Seeders;
    use Illuminate\Database\Seeder;
    class DatabaseSeeder extends Seeder
    {
        /**
        * Seed the application's database.
        *
        * @return void
        */
        public function run()
        {
            // \App\Models\User::factory(10)->create();
            $this->call(PostsTableSeeder::class);
        }
    }
#### 运行数据库填充命令填充初始化数据 
php artisan db:seed
该命令执行成功后，posts 表中会多出 20 行记录：



### 创建配置文件
我们还需要为博客做一些配置，比如标题和每页显示文章数。时间不多了，让我们快速行动起来。
在 config 目录下创建一个新的配置文件 blog.php，编辑其内容如下： 
    <?php
    return [
            'title' => 'My Blog',
            'posts_per_page' => 5
    ];
在 Laravel 中，可以轻松通过辅助函数 config() 访问这些配置项，例如，config('blog.title') 将会返回 title 配置项的值。
此外，如果需要的话你还可以去 config/app.php 修改时区配置。


### 创建路由和控制器
#### 修改 routes/web.php 文件如下： 
    <?php
    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\BlogController;
    Route::get('/', function () {
        return redirect('/blog');
    });
    Route::get('/blog', [BlogController::class, 'index'])->name('blog.home');
    Route::get('/blog/{slug}', [BlogController::class, 'showPost'])->name('blog.detail');
这样，如果访问 http://blog.test/ 的话，页面会重定向到 http://blog.test/blog，而访问 http://blog.test/blog 时，会调用 BlogController 的 index 方法来处理业务逻辑并渲染页面。同理访问 http://blog.test/blog/POST-TITLE 时，会调用 BlogController 的 showPost 方法，同时会将 POST-TITLE 的值作为参数传递给 showPost 方法。
#### 创建这个控制器 BlogController。
##### 首先，使用 Artisan 命令生成一个空的控制器： 
php artisan make:controller BlogController
##### 一个新的 BlogController.php 文件已经生成到 app/Http/Controllers 目录下，编辑其内容如下： 
    <?php
    namespace App\Http\Controllers;
    use App\Models\Post;
    use Carbon\Carbon;
    use Illuminate\Http\Request;
    class BlogController extends Controller
    {
        public function index()
        {
            $posts = Post::where('published_at', '<=', Carbon::now())
                ->orderBy('published_at', 'desc')
                ->paginate(config('blog.posts_per_page'));
            return view('blog.index', compact('posts'));
        }
        public function showPost($slug)
        {
            $post = Post::where('slug', $slug)->firstOrFail();
            return view('blog.post', ['post' => $post]);
        }
    }
在控制器中，我们使用 Eloquent ORM 与数据库进行交互，并使用辅助函数 view() 渲染视图。
#### 如果要查看应用中的所有路由，可以使用如下命令： 
php artisan route:list


### 创建视图
剩下的就是创建两个视图用来显示结果了：一个用于显示文章列表，一个用于显示文章详情。
#### 创建视图文件 index.blade.php
    在 resources/views 目录下创建一个新的目录 blog。然后在该目录下创建一个新的视图文件 index.blade.php。使用 .blade.php 后缀的目的在于告知 Laravel 该视图文件使用 Blade 模板。编辑 index.blade.php 文件内容如下： 
    <html>
    <head>
        <title>{{ config('blog.title') }}</title>
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>
    <body>
    <div class="container">
        <h1>{{ config('blog.title') }}</h1>
        <h5>Page {{ $posts->currentPage() }} of {{ $posts->lastPage() }}</h5>
        <hr>
        <ul>
            @foreach ($posts as $post)
                <li>
                    <a href="{{ route('blog.detail', ['slug' => $post->slug]) }}">{{ $post->title }}</a>
                    <em>({{ $post->published_at }})</em>
                    <p>
                        {{ str_limit($post->content) }}
                    </p>
                </li>
            @endforeach
        </ul>
        <hr>
        {!! $posts->render() !!}
    </div>
    </body>
    </html>  
#### 创建显示文章详情的视图 post.blade.php
    在 resources/views/blog 目录下新建视图文件 post.blade.php，编辑其内容如下：
    <html>
    <head>
        <title>{{ $post->title }}</title>
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>
    <body>
    <div class="container">
        <h1>{{ $post->title }}</h1>
        <h5>{{ $post->published_at }}</h5>
        <hr>
        {!! nl2br(e($post->content)) !!}
        <hr>
        <button class="btn btn-primary" onclick="history.go(-1)">
            « Back
        </button>
    </div>
    </body>
    </html>
### 测试
好了，接下来我们可以去浏览器中进行测试了，访问 http://blog.test 。



## License
The laravel-blog-simple is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
