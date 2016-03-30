# jQit_crons

These bot scripts are to be used in conjunction with **jQit** (jQuery Issue Tracker).   
They will traverse the GitHub API to acquire all the jquery GitHub API data that will be required to run a successful tracker.

### Implement cronjobs  
Note: the following files should be called in this order.   
It is up to you how many of times a day these files will acquire data.  
Start by calling the ``repoPullData_init.php`` file in the cronjob.    
 
EXAMPLE:   
Once a day at midnight.
```

0 0 * * *  /home/somedir//src/jqitBot.php

```

To Test jQit-Bot ability to acquire the correct data. Simply run

```

$ curl  /home/somedir//src/jqitBot.php

```
