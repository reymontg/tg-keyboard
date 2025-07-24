# Authorization

<a name="introduction"></a>
## Introduction

For creating InlineKeyboards you can simply use:

If you need to create a keyboard you can use the classes provided by this package as a drop-in replacement.

This is best explained with an example:

```php
use Reymon\Type\Keyboard\KeyboardInline;

$keyboard = KeyboardInline::new();
```

<a name="add-buttons"></a>
## Add Buttons
In order to add buttons you can use `addButtons` method which will be too boring.
So we have simple methods like:

```php
use Reymon\Type\Keyboard\KeyboardInline;

$keyboard = KeyboardInline::new();
$keyboard->addCallback('text', 'callback');
$keyboard->addCallbacks([
    'row1-text1' => 'callback1',
    'row1-text2' => 'callback2',
], [
    'row2-text1' => 'callback3',
    'row2-text2' => 'callback4',
]);
```