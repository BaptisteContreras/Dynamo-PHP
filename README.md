# Dynamo-PHP 

This project is a try to implement the Amazon Dynamo paper (see docs/amazon-dynamo-sosp2007.pdf) using PHP and Symfony.

**Dynamo-PHP** is a Datastore with high availability and strong resilience at the cost of eventual consistency under some conditions.
This application only support single object transaction with no isolation level, but you are assured that all your writes will succeed.


## Node
PHP application that is part of the Ring