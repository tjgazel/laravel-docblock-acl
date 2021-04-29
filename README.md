# Docblock ACL para Laravel

[![Latest Stable Version](https://poser.pugx.org/tjgazel/laravel-docblock-acl/v/stable)](https://packagist.org/packages/tjgazel/laravel-docblock-acl)
[![License](https://poser.pugx.org/tjgazel/laravel-docblock-acl/license)](https://github.com/tjgazel/laravel-docblock-acl/blob/master/LICENSE)
[![Total Downloads](https://poser.pugx.org/tjgazel/laravel-docblock-acl/downloads)](https://packagist.org/packages/tjgazel/laravel-docblock-acl)

[English docs](README_en.md)

Este pacote usa o docblock das ações dos controladores para definir permissões de acesso ao seu sistema. Foi idealizado e desenvolvido 
originalmente para Zend-framework 2 por Thales F. Santos e Giovanni Camargo.

**Compatível com Livewire Full-Page Components.**

<br/>

| Releases | Laravel | Docs | Obs. |
| ------- | -------------- | -----| ---- |
| v1.* | 5.5+, 6.* e 7.* | [Ver](https://github.com/tjgazel/laravel-docblock-acl/tree/masterV1) | Suporta apenas um grupo de permissões por usuário. |
| v2.* | 6.* e 7.* | [Ver](https://github.com/tjgazel/laravel-docblock-acl/tree/masterV2) | Suporta vários grupos de permissões por usuário. |
| v3.* | 8.* | [Ver](https://github.com/tjgazel/laravel-docblock-acl/tree/master) | Suporta vários grupos de permissões por usuário. |


<br/>

-   [Instalação](#Instalação)

-   [Configurações](#Configurações)

    -  [Migrations e seeder](#Migrations-e-seeder)
    -  [Models](#Models)
    -  [Middleware](#Middleware)
    -  [Rotas ACL](#Rotas-ACL)
    -  [Views](#Views)
    -  [Mostrando mensagens de erro ou sucesso](#Mostrando-mensagens-de-erro-ou-sucesso)

-   [Exemplo de uso](#Exemplo-de-uso)

    -   [Mapeando recursos](#Mapeando-recursos)
    -   [Mapeando permissões](#Mapeando-permissoes)
    -   [Livewire Full-Page Components](#Livewire-Full-Page-Components)
    -   [Protegendo rotas com ACL](#Protegendo-rotas-com-ACL)
    -   [Utilizando Gate, Can, Middleware e Blade ](#Utilizando-Gate,-Can,-Middleware-e-Blade)

<br/><br/>

# Instalação

Use o composer

```bash
composer require tjgazel/laravel-docblock-acl
```

<br>

Após instalação ter sido concluída, rode o comando abaixo.

```bash
php artisan vendor:publish --provider="TJGazel\LaravelDocBlockAcl\AclServiceProvider"
```

Este comando irá fazer as seguintes alterações:

-   Adicionar o arquivo de configuração `acl.php` no diretório `config`.
-   Adicionar as migrações para o ACL.
-   Adicionar um arquivo de seeder.
-   Adicionar as views do ACL em `resources/views/vendor/acl`.
-   Adicionar os aquivos de tradução em `resources/lang/vendor/acl/en` e `resources/lang/vendor/acl/pt_BR`.

Como alternativa você pode publicar cada etapa de forma individual usando o comando `php artisan vendor:publish` e selecionando a respectiva opção na lista.

<br/><br/>

# Configurações

Abra o arquivo `config/acl.php` e configure o namespace correto para sua model `User`.

```php
return [
    'model' => [
        'user' => '\App\User',
        'group' => '\TJGazel\LaravelDocBlockAcl\Models\Group',
        'permission' => '\TJGazel\LaravelDocBlockAcl\Models\Permission',
    ],
    
    'table' => [
        'users' => 'users',
        'groups' => 'groups',
        'permissions' => 'permissions',
        'group_permission' => 'group_permission',
        'group_user' => 'group_user',
    ],

    'session_error' => 'acl_error',

    'session_success' => 'acl_success',
];
```
<br/><br/>

### Migrations e seeder

![Screenshot 01](./screenshot03.png)

```bash
php artisan migrate
```

```bash
php artisan db:seed --class=AclTablesSeeder
```

> O seeder Acl irá popular os grupos e permissões iniciais. Você deverá ligar manualmente um id de usuário ao grupo
> Admin (id 1) na tabela pivot `group_user` para ter acesso ao painel de controle de permissões.

<br/><br/>

### Models

No model `User`, implemente a interface `UserAcl`. Adicione também a trait `UserAcl`.

```php
// ....
use TJGazel\LaravelDocBlockAcl\Models\Contracts\UserAcl as UserAclContract;
use TJGazel\LaravelDocBlockAcl\Models\traits\UserAcl as UserAcltrait;

class User extends Authenticatable implements UserAclContract
{
    use Notifiable, UserAcltrait;
}
```

<br/><br/>

### Middleware

Abra o arquivo `app/Http/Kernel.php` e adicione o middleware ACL.

```php
protected $routeMiddleware = [
    //...
    'acl' => \TJGazel\LaravelDocBlockAcl\Http\Middleware\AclMiddleware::class,
];
```
<br/><br/>

### Rotas ACL

Adicione em seu arquivo de rotas Api ou Web a função abaixo. As rotas irão responder de acordo com o tipo de solicitação. Lembre-se que ACL funciona em conjunto com o login. Assumimos então que você já tenha instalado o sistema padrão de login do laravel usado `php artisan make:auth`

```php
// web.php
Auth::routes();

Acl::routes();
```

A função de rotas aceita um array opcional de configurações para fácil personalização do prefixo, nome e middlewares padrões da rota. Por padrão ele usa o seguinte modelo.

```php
Acl::routes([
    'middleware' => ['auth', 'acl'],
    'prefix' => 'acl',
    'name' => 'acl.'
]);
```

> **Importante:**
> Como é obvio, no primeiro momento não exite uma atribuição das permissões para determinado grupo. A configuração das rotas `Acl::routes()` , aplica o middleware acl por padrão conforme mostrado acima, sendo assim, será impossível acessá-las sem desativar monetanteamente esse middleware. Para isso passe o parâmetro opcional conforme mostrado abaixo.

```php
Acl::routes([
    'middleware' => []
]);
```

Não esqueça de voltar o padrão após atribuição das permissões aos grupos essenciais.

<br/><br/>

**Lista das rotas ACL** <br>
Podem ser personalizadas com prefix, name e middleware conforme mostrado acima.

| Method | URI                  | Name        | Controller                                                         | Middleware |
| ------ | -------------------- | ----------- | ------------------------------------------------------------------ | ---------- |
| GET    | /acl                 | acl.index   | \TJGazel\LaravelDocBlockAcl\Http\Controllers\AclController@index   | auth,acl   |
| POST   | /acl                 | acl.store   | \TJGazel\LaravelDocBlockAcl\Http\Controllers\AclController@store   | auth,acl   |
| GET    | /acl/create          | acl.create  | \TJGazel\LaravelDocBlockAcl\Http\Controllers\AclController@create  | auth,acl   |
| GET    | /acl/{group_id}/edit | acl.edit    | \TJGazel\LaravelDocBlockAcl\Http\Controllers\AclController@edit    | auth,acl   |
| PUT    | /acl/{group_id}      | acl.update  | \TJGazel\LaravelDocBlockAcl\Http\Controllers\AclController@update  | auth,acl   |
| DELETE | /acl/{group_id}      | acl.destroy | \TJGazel\LaravelDocBlockAcl\Http\Controllers\AclController@destroy | auth,acl   |

<br/><br/>

### Views

As views ACL extendem o template `resource/views/layouts/app.blade.php` criado pelo comando `php artisan make:auth`. Você pode edita-las a seu gosto. Path `resources/views/vendor/acl`.

**Idiomas:** views e mensagens de erro.

-   en
-   pt_BR

`localhost:8000/acl`
![Screenshot 01](./screenshot01.png)

`localhost:8000/1/edit`
![Screenshot 01](./screenshot02.png)

<br/><br/>

### Mostrando mensagens de erro ou sucesso

As mesagens são enviadas através de `flash message` com as respectivas chaves de acordo com o arquivo `config/acl.php`. Você pode exibi-las usando seu próprio modelo.

```php
@if( session(config('acl.session_error')) )
    {{ session(config('acl.session_error')) }}
@endif
@if( session(config('acl.session_success')) )
    {{ session(config('acl.session_success')) }}
@endif
```

O pacote também tráz o modelo boostrap 4 e já faz todo este trabalho. Inclua o arquivo no seu modelo de layout.

```php
@include('acl::_msg')
```

> Como alternativa, esse pacote dá suporte ao [tjgazel/laravel-toastr](https://github.com/tjgazel/laravel-toastr) que traz uma interface mais amigável na exibição de flash message. Se você já usa [tjgazel/laravel-toastr](https://github.com/tjgazel/laravel-toastr) não precisa fazer nenhuma configuração extra, já está tudo pronto.

<br/><br/>

# Exemplo de uso

### Mapeando recursos

Em seus controllers utilize docblocs com a tag `@permissionResource('Nome do recurso')`.

```php
/**
 * @permissionResource('ACL')
 */
class PageController extends Controller
{
    //...
}
```

<br/><br/>

### Mapeando permissões

Nas ações (methods) do controller utilize a tag `@permissionName('Nome da permissão')`

```php
    /**
     * @permissionName('list all groups')
     */
    public function index()
    {
        //...
    }

    /**
     * @permissionName('Edit - Form')
     */
    public function edit($id)
    {
        //...
    }

    /**
     * @permissionName('Update')
     */
    public function update()
    {
        //...
    }
```

> Varios controllers podem usar o mesmo nome de recurso `**@permissionResource('ACL')**`. Isto fará com que as permissões de todos os controladores que contenham o mesmo nome de recurso, sejam mostradas na view de forma agrupada, mas tenha atenção em colisão de nomes nas tag das permissões `**@permissionName('Nome da permissão')**`.

Após esse mapeamento, acesse a rota `**localhost:8000/acl**` para que o sistema registre de fato as permissões no banco de dados. Quando houver qualquer alteração esse procedimento deve ser repetido para registrar as alterações.

<br/><br/>

### Livewire Full-Page Components
Ao utilizar Livewire Full-Page Components só é possível mapear o método render, então leve em consideração
que cada componente deve ter uma ação bem específica para manter a semantica do ACL.

```php
Route::get('posts/create', 'Livewire\Posts\PostCreate');
```

```php
namespace App\Http\Livewire\Posts;

use Livewire\Component;

/** @permissionResource('Posts') */
class PostCreate extends Component
{
    /**
     * @permissionName('Create')
     */
    public function render()
    {
        return view('livewire.posts.create')->layout('layouts.app');
    }
    
    public function onSubmit()
    {
        //...
    }
}
```

<br/><br/>

### Protegendo rotas com ACL

Basta agrupar suas rotas com o middleware ACL.

```php
Route::middleware(['auth', 'acl'])->group(function() {
    //...
});
```

> **Observação:**
> Antes de proteger suas rotas, faça o mapeamentos dos recursos e permissões nos controllers e através da rota (por padrão) `/acl` , atribua as permissões aos tipos de usuários.
>
> ACL funciona em conjunto com autenticação, portando recomenda-se mapear apenas as ações dos controladores que estiverem protegidas pelos middlewares `auth`, `auth:api` ou outro personalizado.

<br/><br/>

### Utilizando Gate, Can, Middleware e Blade
Após mapear os controllers da aplicação, serão geristrados automaticamente [Gates](https://laravel.com/docs/7.x/authorization#gates) comforme a documentação oficial do Laravel ([Ver na documentação](https://laravel.com/docs/7.x/authorization)) oferecendo todos os recursos contidos no framework Laravel.

O nome para cada permissão(Gate) segue um padrão de moneclatura `nome_do_recurso.nome_da_permissao`.

Seguindo o exemplo do mapeamento do controller na sessão acima teremos as seguintes permissões mapeadas no sistema:
- `acl.list_all_groups`
- `acl.edit_form`
- `acl.update`

> *OBS:* Para normalizar os nomes, foi utilizado a facade `Str::slug()`. 

Segue alguns exemplos de uso:

<br/>

O usuário deve pertencer a um grupo que contém uma permissão expecífica (`acl.list_all_groups`) para visualizar o link `Permissões do sistema`. Utilizando a diretiva `@can` do Blade podemos facilmente fazer isso.

```html
<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column nav-flat" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-item">
            <a href="{{route('painel.index')}}" class="nav-link">Dashboard</a>
        </li>
        @can('acl.list_all_groups')
        <li class="nav-item">
            <a href="{{route('painel.acl.index')}}" class="nav-link">Permissões do sistema</a>
        </li>
        @endcan
    </ul>
</nav>
```

<br/><br/>

Em qualquer ponto da aplicação que tiver acesso a instância do usuário logado.
```php
if (Auth::user()->can('acl.update')) {
    <!-- O usuário tem permissão para atualizar os dados -->
}
```

<br/><br/>

Utilizando Gates.
```php
if (Gate::authorize('acl.edit_form')) {
    <!-- O usuário tem permissão para acessar a view de edição -->
}
```

<br/><br/>

**Utilizando middleware**. Apesar do middlware padrão `Acl` oferecido pelo pacote (Ver [Protegendo rotas com ACL](#Protegendo-rotas-com-ACL)) resolver a maioria dos casos, você pode aplicar uma permissão expecífica em uma rota.

```php
Route::put('/post/{post}', function (Post $post) {
    // The current user may update the post...
})->middleware('can:post.update'); // can:nome_do_recurso.nome_da_permissao
```

<br/><br/>

[Veja todos os exemplos de uso na sessão **Authorization** da documentação oficial do Laravel](https://laravel.com/docs/7.x/authorization)