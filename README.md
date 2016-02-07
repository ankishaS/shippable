# shippable
Test Code for shippable

CODE SYNOPSIS -
A simple php application that reads a link to any public GitHub repository and displays the following information

- Total number of open issues
- Number of open issues that were opened in the last 24 hours
- Number of open issues that were opened more than 24 hours ago but less than 7 days ago
- Number of open issues that were opened more than 7 days ago 

SOLUTION -

Step 1: Take URL Example: https://github.com/Shippable/support/issues as an input 
Step 2: Check for valid url 
Step 3: Preparing gitHub api url from input url 
Step 4: Executing curl commands for getting response 
Step 5: Displaying data in a html table

FUTURE ENHANCEMENTS-

Given time following improvements can be added- 
1. Improvement in data representation eg.  pie-charrepresentation of issues
2. Improvement in  code logic - badresponse handling 

 URL FOR LIVE APPLICATION -
  https://stormy-castle-35953.herokuapp.com/shippable.php
 
  

