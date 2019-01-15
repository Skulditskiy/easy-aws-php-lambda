# Easiest way to start creating AWS Lambda functions with PHP

## How to create Lambda function 
- run `composer update`
- run `zip -j -r runtime.zip runtime/*`
- run `zip -r function.zip src/* vendor/*`
- go to AWS console and create lambda function. Choose "custom runtime".
- upload `function.zip` as "Function code".
- set handler to `dummy.handler`
- create new layer by uploading `runtime.zip`
- run function, it is ready.

## Side notes
- do not use composer dependencies in runtime package. Otherwise it will be hard to update separately runtime and function code
- cool "side effect" is that your all your included files will be included only once on first run. Consecutive runs will already have all autoloaded files. That will save millisecond(s).
- "cold run" for that example takes ~30ms. "warm" - less than 2ms. 
- seems that "classic" PHP frameworks does not really fit into that model: neither HTTP, nor console. However for both cases you can still use them (manually creating Request and/or Console Command from payload of function)
- In that example you can see example DI container, feel free to use yours (from Slim, for example). However, keep in mind that all created members of DI will be kept between execution. 

## Other useful tutorials:
- https://github.com/pagnihotry/PHP-Lambda-Runtime
- https://aws.amazon.com/blogs/apn/aws-lambda-custom-runtime-for-php-a-practical-example/
- http://p.agnihotry.com/post/php_aws_lambda_runtime/

## How it is all works together
Your custom runtime is an archive which must contain `bootstrap` file. From that file you can access to other files you included in your custom runtime package. We don't need anything except php binary and some PHP script which will be our broker. 

In runtime.zip AWS executes `bootstrap` file. It just proxies control to `runtime.php` with using our php binary.

In `runtime.php` we are waiting for next call to our lambda function and when it happens executing our handler function. Also, at that point we have all dependencies and can pass to handler such things like API clients, Factories and other dependencies.

In `index.php` could be more than one handler. You can create another Lambda function with handler `another.handler` and all you need to do is to create another function `another` in `index.php`
