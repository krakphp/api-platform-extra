# API Platform Extra

API Platform Extra includes additional features or bug fixes into API Platform that haven't been released or won't be released.

## Installation

Install with composer at `krak/api-platform-extra`.

## Usage

By default all functionality provided by this library is *opt-in*, so you'll need to explicitly enable each feature in the config to make use of the features.

### MessageBusDataPersister

The message bus data persister is enabled by default and controlled via the following config:

```yaml

api_platform_extra:
    enable_message_bus_data_persister: true
```

The `MessageBusDataPersister` will push any messages that aren't handled by the standard data persisters into the message bus.

So the only thing you need to do is simply just register message handlers for the ApiPlatform resources you want to handle via the message bus.

### Additional Swagger Documentation

This allows you to merge and override in any additional swagger documentation to the generated swagger documentation from API Platform. This is useful if you need to provide any custom endpoints outside of your defined API Platform resources.

The configuration for defining the swagger file is here:

```yaml
api_platform_extra:
    additional_swagger_path: "%kernel.project_dir%/config/api_platform/swagger.yaml"
```

If that file exists, the additional swagger documentation will be merged. Else, nothing will happen.

### Plural Data Path Segment Name Generator

By default, API Platform's inflector will transport any resource ending with `Data` as `Datas`. This fixes this specific use case, and could possibly extended to adjust multiple inflection fixes.

### Constructor Denormalizer

This issue has actually been fixed but is pending release https://github.com/api-platform/core/pull/2178. This library will include that patch until api platform 2.4.0 is released.

This basically allows you to use constructor arguments with nested entities like so:

```php
<?php

namespace App\Entity;

class Book
{
    private $id;
    private $name;
    private $author;
    private $createdAt;

    public function __construct(string $name, Author $author) {
        $this->name = $name;
        $this->author = $author;
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getAuthor(): Author {
        return $this->author;
    }

    public function getCreatedAt(): \DateTimeInterface {
        return $this->createdAt;
    }
}
```

The actual POST API request for this would look like:

```json
{
    "name": "Book Name!",
    "author": "/authors/1"
}
```

```yaml
api_platform_extra:
    enable_constructor_deserialization: true
```

### Operation Resource Classes

This feature is controlled via the following configuration:

```yaml
api_platform_extra:
    enable_operation_resource_class: true
```

When enabled, this will allow with very simple configuration, the ability to define a specific resource class per operation. This is especially useful on POST collection operations where you want to use a special DTO resource instead of the actual Entity resource.

Here's how you'd set it up:

```php
<?php

// under App\Entity
class Book
{
    private $id;
    private $name;
    private $author;

    public function __construct(string $name, Author $author) {
        // assign
    }
}

// under App\DTO
class CreateBookRequest
{
    public $name;
    public $authorName;

    public function __construct(string $name, string $authorName) {
        $this->name = $name;
        $this->authorName = $authorName;
    }
}

// under App\Service
class CreateBook
{
    public function __invoke(CreateBookRequest $req): Book {
        // use the req data to actually create then pesist/flush the Book instance
    }
}
```

Then, you can configure the resources with the following yaml. You could use annotations or xml, but I find it more manageable to use yaml than either:

```yaml
App\Entity\Book:
  collectionOperations:
    get: ~
    post:
      resource_class: App\DTO\CreateBookRequest
App\DTO\CreateBookRequest:
  attributes:
    schema_only: true
```

This will make sure the documentation and the API use the schema of the `CreateBookRequest` instead of the normal `Book` entity schema. Using the `schema_only` attribute for the DTO ensures that no paths will be generated for that resource.

You'll need to make sure the Message Bus Data Persister is enabled so that the wiring of the Request to the `CreateBook` Service is done properly.


### Definition Only Resources

There may be certain Resources that you want to only use for definitions, but use custom endpoints/swagger docs around them, to do that, you can just use the `schema_only: true` attribute.

### ApiProperty Override

Any definitions in the ApiProperty annotation do not override changes made from the Serializer Property Metadata Factory. You can control the ability for ApiProperty to override with the following option:

```yaml
api_platform_extra:
    enable_overriding_annotation_property_metadata_factory: true
```
