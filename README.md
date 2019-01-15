## How to create Lambda function 
- run `composer update`
- run `zip -r runtime.zip runtime/*`
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
