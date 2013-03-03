Very simple error logging package. Catches uncaught exceptions, and logs them via PHP web service (slim framework).

All you need to do is just instantiate Reporter class:

``final Reporter report = new Reporter("http://localhost/ws/submit"); // reporter must be final
report.setKey("54rGRDG!#@$");``

and all uncaught exceptions will be sent to the webservice.
If there's no internet connection, they will be queued and send when possible.
