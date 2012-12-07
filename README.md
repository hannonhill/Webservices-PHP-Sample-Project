Webservices-PHP-Sample-Project
==============================

This project contains example operations that can be executed through Cascade Server 
Web Services using PHP. 

------------------------------

The php_soap library needs to be enabled to be able to execute the code.

To be able to connect to your Cascade Server instance, you should update the $soapURL 
with the URL to your instance's WSDL and the $auth with your username and password.

------------------------------

The "location" parameter in $client is optional - it is only needed when you are getting
"Could not connect to host" error when performing operations. In that case most likely 
Cascade Server is using apache proxy (using mod_proxy). Sometimes when running 
through a proxy, the returned service endpoint (at the very bottom of the WSDL 
response) points to a location that is an internal hostname/port.

In that case, the PHP SoapClient is parsing the WSDL and using that service endpoint
to issue requests. So even though it looks like you're connecting to the soapURL,
you're actually connecting to an internal hostname/port so you might get: Could not
connect to host.

The "location" param is way to manually set the service endpoint instead of letting
the SoapClient figure it out from the WSDL.