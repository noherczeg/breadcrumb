##Laravel 3 specific guide
This document will show you an example how you __could__ use this package with Laravel 3, and is not an installation/setup guide!

### Using Breadcrumb with a base controller
Breadcrumbs are usually rendered on multiple pages of a website so it might come handy to make it so that we would be able to create them in a blink of an eye / automatically. To do this one way is to use a base controller, which can be your own, or the default one which comes with Laravel. The only thing that you should keep in mind is that __you have to extend that controller__, otherwise you'll get errors, and won't get access to breadcrumbs either!

#####We will for now use the default base controller `application/controllers/base.php`:
```
<?php use Laravel\Redirect;

class Base_Controller extends Controller
{

    public $breadcrumb = null;
    [...]

    public function before()
    {
        $this->breadcrumb = new \Noherczeg\Breadcrumb\Breadcrumb(URL::base());

        // if there is nothing in the URI
        if (strlen(URI::current()) == 0 ) {

            // we still want a link which points to the base URL
            $this->breadcrumb->append('welcome', 'left', true);
        } else {

            // otherwise add the URI elements as well
            $this->breadcrumb->from(URI::current())->append('welcome', 'left', true);
        }
        [...]
    }

   [..]
}
```

#####In any of your controllers where you want to use breadcrumbs (e.g. `application/controllers/home.php`):
```
<?php

class Home_Controller extends Base_Controller
{
    public function action_index()
    {
        // we'll pass bootstrap styled breadcrumbs to the view
        return View::make('home.index')
            ->with('breadcrumb', $this->breadcrumb->build('bootstrap'));
    }

}
```

#####And lastly in our corresponding view (e.g. `application/views/home/index.blade.php`):
```
<!doctype html>
<html lang="en">
    <head>
        <!-- header data -->
    </head>
    <body>
        <!-- Render our breadcrumbs somewhere -->
        <p>{{ $breadcrumb }}</p>
        <h2>Page title goes here</h2>
        <p>Content goes here :)</p>
        <!-- Other stuff... -->
    </body>
</html>
```