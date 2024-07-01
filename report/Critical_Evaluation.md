# Critical Evaluation

## Introduction
This report critically evaluates the processes undertaken in implementing a Restful API which is a currency conversion microservice with full CRUD functionality using PHP and a responsive client form using HTML and JavaScript. Moreover, it highlights and discusses learning outcomes, potential enhancements, and reusability aspects of this web service.


## Overall Process and Learning Outcomes
In the process of building this currency conversion microservice, a practical understanding of designing and developing RESTful API using PHP to handle HTTP methods such as POST, PUT, DELETE is gained. And managing XML data using XPath and SimpleXML functions in PHP also contributed to a solid foundation in currency API development which is crucial for storing and retrieving structured XML in a web environment. 

Modularization approach such as breaking down the application into modular components such as `generateConversionRates.php`, and `handleUpdateErrors.php` is used for greater code organization and maintainability (Richardson, 2019). Functions are also designed for specific tasks, such as updating currencies, which facilitates reusability by allowing to be easily integrated into other components. Furthermore, the benefits of microservices patterns are also learnt from those designings to manipulate currency data retrieval and conversion through a set of URL endpoints.

Robust errors handling was another essential learning result which ensured in maintaining the reliability of the application, especially when dealing with unexpected events.

Finally, implementing AJAX calls and Document Object Model using JavaScript enabled the client and server not only to exchange data asynchronously enhancing user experience with seamless updates without reloading the entire page but also to make integration the API with the front-end interface. 


## Application Extension and Improvement

### Enhanced User Interface
This currency conversion web microservice can be further improved to provide real-time information to users and updating them dynamically on the interface. JavaScript libraries such as React can be used to leverage a more dynamic and user-friendly interface (Davies). Moreover, implementing client-side form validation can enhance the user experience for better validity and security of user inputs.

### Scalability and Security 
Enhanced security measures such as user authentication and authorization, can be extended in this application production for robustness which will ensure only authorized users to perform certain actions. Load balancing and API versioning can be integrated to manage network traffic and maintain performance even during peak usage periods as well as introduce new features and changes to the API without disrupting existing codes (Jindal, 2020).

### Database Integration
Transitioning from XML storage to a relational or non-relational database such as MySQL or Mongo database for managing currency data can improve currency data retrieval efficiency handling more complex queries.


## Promoting Reusability

### Containerization
Since the application has been developed as the combination of small components, each having a specific function, a highly reusable currency conversion microservice can be created by using containers, such as Docker, in this microservices architecture as they provide better flexibility and simplicity of maintenance as opposed to traditional monolithic application design (OpsLevel, 2023).
Moreover, this technique can promote modern, cloud-native application development and deployment, and readily integrated into a variety of other applications (Google Cloud).

### Open-Source Contribution
Contributing these currencies conversion codes to open-source projects for wider community can also be considered which enables other developers to freely access for collaborative improvement of reusable components.

### Comprehensive Documentation
Providing a clear and comprehensive documentation for each component promotes the reuse of code by making it easier for other developers to quickly understand covering usage instructions and input requirements for easy adoption and integration. 


## Conclusion
In conclusion, building this currency conversion microservice provided valuable insights into PHP-based web development, AJAX implementation, and code organization and reusability best practices. The potential enhancements help to provide a roadmap for improving the currency conversion application's functionalities for future developments.


## Bibliography 

Davies, A. (no date) *How to Boost Web Application Performance?*. Available from: https://www.devteam.space/blog/boost-web-application-performance/ [Accessed 7 February 2024].

Google Cloud (no date) *What is Microservices Architecture?*. Available from: https://cloud.google.com/learn/what-is-microservices-architecture [Accessed 11 February 2024].

Jindal, A. (2020) *Tips For Scaling Web Application Security*. Available from: https://www.indusface.com/blog/tips-for-scaling-web-application-security/ [Accessed 8 February 2024].

OpsLevel (2023) *What is code reuse and why is it important?*. Available from: https://www.opslevel.com/resources/what-is-code-reuse-and-why-is-it-important [Accessed 10 February 2024].

Richardson, C. (2019) *Microservices patterns: with examples in Java*. 1st ed. Shelter Island, New York: Manning Publications.