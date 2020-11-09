# ansize
Use ANSI to print an image

### Usage
```php
php ansize.php <filename> <width> <height>
```

One day, I wandered around GitHub, and I found an interesting project named [ansize](https://github.com/jhchen/ansize), 
but it was written in go language, so I want to use PHP to archive it.

### Problems
- How to print colorful text in ANSI?

  https://stackoverflow.com/questions/4842424/list-sof-ansi-color-escape-sequences
- How to convert RGB value to an available for ansi?

  https://stackoverflow.com/questions/15682537/ansi-color-specific-rgb-sequence-bash

### Example
```php
php ansize.php ./example/wechat_avatar.jpeg 100 50
```
![stdout](./example/stdout.png)

DONE, I'M HUNGRY.