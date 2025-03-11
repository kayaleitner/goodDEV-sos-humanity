# BlockImageText

## Overview
`BlockImageText` displays media (image/video) alongside formatted text in a responsive grid layout. It supports customizable positioning, captions, and WYSIWYG content with optional title.


## ACF Fields

### **Media Tab**
| Field Name      | Type       | Description                 |
|-----------------|------------|-----------------------------|
| `mediaPosition` | Select     | Position media left/right   |
| `mediaType`     | Select     | Choose image or video       |
| `image`         | Image      | Upload image file           |
| `video`         | Group      | Video configuration group   |


#### Video Group Fields
| Field Name     | Type     | Description                    |
|----------------|----------|--------------------------------|
| `label`        | Text     | Video accessibility label      |
| `posterImage`  | Image    | Video thumbnail                |
| `videoFiles`   | Repeater | Multiple video format support  |


### **Content Tab**
| Field Name     | Type    | Description                     |
|----------------|---------|---------------------------------|
| `blockTitle`   | Text    | Optional H2 heading             |
| `contentHtml`  | WYSIWYG | Main content area               |


### **Options Tab**
| Field Name         | Type        | Description             |
|--------------------|-------------|-------------------------|
| `componentId`      | Text        | Unique component ID     |
| `colorBackground`  | ColorPicker | Background color        |
| `colorText`        | ColorPicker | Text color              |


## Macros Used
- `lazyImage`: Image loading with lazy load support
- `video`: Video player with poster image
- `title`: Heading component with font variations


## Usage
```php
Flynt\Components\BlockImageText\getACFLayout()
```