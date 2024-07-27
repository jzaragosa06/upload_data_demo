# Demonstration of uploading the time series data and handling missing values. 

##How to run
Clone the repository. In the terminal run `composer install`. To run the application, in the terminal type `php artisan serve`. Navigate to `localhost:8000/upload`.

## Required modifications
Modify the code to meet the following requirements: 
- Handle different date formats. It should work on different dateformat (mm/dd/yyyy or yyy-mm-dd etc).
      - Tip: Just convert the index date into the format that is required by the graphing library (plotly). 
- Handle the case when the index is in date format. Graph the data accordingly.


## Reminder
Clicking the `next` button will generate a csv, which will be forwarded to the controller. Don't modify this part. The link is there so that you can see the end result of the data manipulation. 
