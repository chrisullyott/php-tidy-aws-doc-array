# php-tidy-aws-doc-array

Convert arrays in the AWS PHP SDK documentation into real code.

Reading AWS documentation, you may wish to use the PHP-style snippets that describe request configurations, [like this one](https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-mediaconvert-2017-08-29.html#createjob).

Use this converter to tidy these arrays and convert them into real code. 

```bash
php tidy <file>
```

The example file contains an entire array from the docs, beginning with the first `[` and ending with the last `]`. The output will be double-spaced and use proper syntax so you can begin to work with the code in your IDE of choice.

```bash
php tidy examples/MediaConvertJob.txt
```

#### Before

```php
[
    'AccelerationSettings' => [
        'Mode' => 'DISABLED|ENABLED|PREFERRED', // REQUIRED
    ],
    'BillingTagsSource' => 'QUEUE|PRESET|JOB_TEMPLATE|JOB',
    'ClientRequestToken' => '<string>',
    'HopDestinations' => [
        [
            'Priority' => <integer>,
            'Queue' => '<string>',
            'WaitMinutes' => <integer>,
        ],
        // ...
    ],
```

#### After

```php
<?php

return [
    'AccelerationSettings' => [
        // REQUIRED
        // DISABLED|ENABLED|PREFERRED
        'Mode' => '<string>', 
    ],

    // QUEUE|PRESET|JOB_TEMPLATE|JOB
    'BillingTagsSource' => '<string>',

    'ClientRequestToken' => '<string>',

    'HopDestinations' => [
        [
            'Priority' => '<integer>',

            'Queue' => '<string>',

            'WaitMinutes' => '<integer>',
        ],
    ],
```

Note that this does not actually _configure_ anything for you. It's still necessary to read the documentation and change or remove the parts of the configuration according to your needs.