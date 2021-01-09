# Technical task #
You need to create a mechanism which will read the CSV file, parse the contents and then insert
the data into a MySQL database table.
The import process will be run from the command line and when it completes it needs to
report how many items were processed, how many were successful, and how many were
skipped. See the import rules below.

### Objectives ###
Your solution must be OO, based on Symfony 2 and use MySQL. Code should be clearly laid out, well commented.
Any SQL used to alter the table should be included as migration scripts with the submission.
Using a command line argument the script can be run in 'test' mode. This will perform
everything the normal import does, but not insert the data into the database.
The supplier provides a stock level and price which we currently do not store. Using
suitable data types, add two columns to the table to capture this information.



# The Solution #

### ParseCsv ###

I used the library [ParseCsv](https://github.com/parsecsv/parsecsv-for-php#parsecsv). This library allows you to read CSV data in a convenient way. 

### Migrations ###

I created 2 migrations: the first migration creates the table, the second migration add 2 new columns: stock and cost. 

### Start the process ###

If you want to run the import process, you should use the command: `php app/console import:csv *path to your csv file*`. For example, `php app/console import:csv /home/andrey/Загрузки/stock.csv`

### Delimiter ###
You can add any valid delimiter (it's optional). If you don't add a delimiter, the delimiter will be auto-detect: `php app/console import:csv *path to your csv file* *delimiter*`. For example, `php app/console import:csv /home/andrey/Загрузки/stock.csv '\t''`

### Test mode ###
Test mode shows you stats without import products: `php app/console import:csv *path to your csv file* --test`. For example, `php app/console import:csv /home/andrey/Загрузки/stock.csv --test`

### Run test for the ImportCSVService class ###
`./vendor/bin/simple-phpunit -c app src/AppBundle/Tests/Service/ImportCSVServiceTest.php`

