# GitHub Integration with drupal 8

This project is a module implementation for drupal 8. The module is a integration between github api and drupal. Allow create a new type field for add to entities. This field allow insert a github username (will validate the username) and show some basic information about it and the sStargazer count.

## Installation

1. Clone the module into the folder *modules*. (For example on this case I used *modules/custom*)
1. This module use a third party library [1]. For install the library we need to add some code to composer.json on the root folder (drupal 8) into the section extra. Should be similar to:
```yml
        "merge-plugin": {
            "include": [
                "core/composer.json",
                "modules/custom/github/composer.json"
            ],
            "recurse": false,
            "replace": false,
            "merge-extra": false
        }
```
2. Go to *admin/modules* and enable Github module.
3. Go to *admin/config/services/github* and add a API key generate from [2]. This point is important because some methods (github API) need a token.
4. Now you can create a new github field or access to */github/ten-last-week*, */github/ten-hottest*, */github/pepper* to get some example page getting data from the github API.

**NOTE**: modules/custom is a example path but you can use another path to install your modules. See https://www.drupal.org/node/2822349 for more info.

**NOTE**: This module implement a service. You can use the service to use any method implemented with the library [1] you can check [3] for more info.


## Because Drupal 8

I used drupal 8 because is the best solution to integrate a API. Drupal 8 include so good components from symfony get a better performance (Dependecy injection, ....) and more maintable code.
TODO: Extend this section

## How test functional requirements

1. First point we will test custom type field. After finish the steps on *Installation* you need go to *admin/structure/types/manage/page/fields*
2. Click on *Add field* on the select *Add a new field* select the option *Github* and insert into *Label* a text. For example *Test*.
3. Click on *Save and continue*
4. Click on *Save field settings* and after this on *Save settings*. Congratulations you have a github field into the content type page.
5. Go now to *node/add/page* and fill title with test and your new field with for example *adfasfasfasfdafasfs*.
6. If you try to submit (Click *Save and publish*) you should see a error message *The github username adfasfasfasfdafasfs is invalid....*
7. Now insert a valid username for example *jacintocapote*
8. And you should see the avatar, username (is a link to github) and **Total stargazer**.
9. The next step is check the API integration. Go first to */github/ten-last-week* and you should see top 10 issues from the last week ordered by comments. On the page you have a more detailed description.
10. Go to */github/ten-hottest* and you should see a top 10 repositories from the last week ordered by stars.
11. Go to */github/pepper* to get a funding report.
12. Last point is a little more complex. Is execute functional test. You have two method for this via browser or via console. On the next section I explain both method

TODO: Add some pictures

## Functional Test

TODO: Add information about because I selected Functional Test and explain about execute both methods

## Funding report

The information on the funding report is based on the next introduction:

Tony Stark has decided to fund GitHub projects based on the following rules:
* The hottest 10 projects (most amount of stars) created per week will receive funding. The project will receive (metrics):
  1. 1 dollar per watcher of the project
  2. 1 dollar per fork of the project
  3. 50 dollars if the project has a wiki
  4. 100 dollars if the project has been downloaded
  5. 10 dollars if any issues have been created
* The report should be in a table format with 
  1. A row per project 
  2. A column per project listing the name, number of stars, and language
  3. A column for each report metric
  5. A total column for each project
  6. A grand total row which sums the investment in each all projects
* The totals for each project should be listed in USD
* The grand total should be listed in USD, and converted on the fly to GBP (Pounds), EUR (Euros), and CHF (Swiss Francs)

## Consideration for developers

TODO: comment about service, dependecy injection, ....

**NOTE**: I supposs you have a fresh drupal 8 installed with standar profile.

[1]: https://github.com/KnpLabs/php-github-api
[2]: https://github.com/settings/tokens
[3]: https://github.com/KnpLabs/php-github-api/tree/master/doc
