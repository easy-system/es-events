Conventions
===========

The event-driven architecture is extremely loosely coupled because the event 
itself doesn’t know about the consequences of its cause. 
Such loose coupling makes the application more flexible, but it increases the 
complexity of the logic of the whole system. 
For a system with large number of components and modules the search of code that 
led to a particular result can be fairly complex.

In this regard, there are two significant limitations to operate with events:
1. As an event listener, possible to use only those listeners that are registered 
   in the system Service Locator.
2. The registration of listeners should be performed only with configuration 
   files.

The system itself must provide the code, that performs event configuration using
the configuration files. The user code must only provide the necessary
configuration files.

In addition, these limitations make it possible to cache all registered events.
