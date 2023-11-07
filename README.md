# OAS Lumen

![Travis (.org)](https://api.travis-ci.com/danballance/oas-lumen.svg?branch=master)

_This package uses [oas-tools](https://github.com/danballance/oas-tools) for schema parsing and utility functions. If you want a handy PHP utility library for working with OAS2 and OAS3 schemas, do feel free to check it out!_

## Introduction

API schemas in general, and OpenAPI Specification schemas in particular, are doing a fantastic job of helping us to create an orderly and well organised internet of RESTful APIs that interoperate easily and play nicely with one another. However most of the projects I see are about auto-generating documentation from server side code - and perhaps additionally generating client or SDK code from those schemas. There seem to be far fewer projects around that are interested the *schema first* approach where the goal is to write our schemas and auto-generate server code from the schemas themselves.

This *schema first* philosophy is my inspiration for putting together this project. Wouldn't it be great if we could spend less time writing repetitive boilerplate code for common functionality and instead just write the specification in JSON or YAML and a few minutes later have an API prototype running? That is the ultimate goal of this oas-lumen project.

I should at this point declare a source of inspiration from the Python community: [Connexion](https://github.com/zalando/connexion). Zalando's Connexion is a truly great project and I worked with it for a couple of years and learn a great deal as a result. However during this time I started to hit a couple of issues with the APIs I was working on.

Firstly, I discovered a tension between the code and functionality that was being auto-generated from the specifications and the additional bespoke code that I was inevitably having to add. I needed a way to auto-generate as much as possible from my initial specification, add custom code of my own, but then go on to change my specification and regenerate code without destroying my bespoke work. Solving that workflow problem in a clean manner is one of the goals of this project.

Secondly, dynamically generating lots of routes, content negotiation and validation rules for each web request is obviously slow in terms of performance. So that's the second problem I want to solve here. The second ultimate goal of this project is to provide a smooth transition from rapid prototyping tool to production code. There needs to be a clearly defined process by which you can take your on-demand prototype and then migrate it into performant code ready for production once the exploratory development phase has been completed.




## Installation

There's a companion repository, [oas-lumen-example](https://github.com/danballance/oas-lumen-example) that demos how an OAS3 schema can be used to bootstrap a Lumen REST API without writing any code. Heading over to the example project and working through the tutorial on the readme there is probably the best way to get a feel for what this project hopes to achieve.

**_WARNING: Alpha / Proof of Concept project. Not production ready! :)_**

However if you do want to install the package directly you can use the package manager [composer](https://getcomposer.org/) to install oas-lumen.

```bash
composer require danballance/oas-lumen
```

## Features

In order to implement the features below, a set of OAS extension parameters need to be added in places to a specification. These are detailed fully in the next section.

* Routes are read dynamically from the schema and assigned to controller/actions
* Default CRUDL controller implementation provides Create, Read, Update, Delete and List actions for the majority of standard use cases.
* A set of "batteries-included", sensible defaults are provided, but everything can be extended and customised s needed
* Any request bodies defined in the API specification can be automatically validated as the request comes in, returning an HTTP 400 status code and explanation of the validation error when an issue is found
* Middleware provided for content negotiation, handling 406 and 415 errors right out of the box
* Plugable serialization based on Accept headers. Initially just application/json and application/hal+json is supported, but more media types can be added (see the roadmap for plans here)
* Generic storage interface that maps OAS schema requests to a storage implementation. If there's something you really need, then write your own storage implementation if you want to!
* The initial, default storage implementation uses Doctrine and could eventually handle both relational ORM and document-based ODM thanks to the Doctrine common project - current implementations can be found [here](https://www.doctrine-project.org/projects/doctrine-persistence/en/latest/reference/index.html#implementations)
* LOTS of plans and ideas for more functionality in the road map! :)

## Specification Extensions

### Operation object extensions

* 'x-action' - Specify the controller action that should used to handle this operation
* 'x-controller' - Specify controller that should be used to handle this operation 
* 'x-resource' - Specify the Resource (or Entity) that this operation belongs to

### Operation.parameter object extensions

* 'x-filter: attribute' - indicate that this parameter is an attribute to be used to query in the List action for retrieving collections of resources
* 'x-filter: limit' - indicate that this parameter performs to role of limit when paginating a collection returned by the List action

### Schema object extensions

* 'x-storage-engine: mysql' (example) - the values for this parameter should be defined by the storage implementation in use. So for example when using the Doctrine storage implementation it will eventually be possible to mix MySQL, PosgreSQL and MongoDB resources all within the one API
* 'x-storage-name: pet' (example) - allows a name of the underlying schema to be specified in cases where it might be different to the name in the Schema. So for example a database table name.
* 'x-primary-key: true' - indicates that this Schema field is a primary key for the underlying storage implementation.

## Roadmap

@TODO coming soon!

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.

## License
[MIT](https://choosealicense.com/licenses/mit/)
