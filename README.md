# Welcome To VuLum

## What is VuLum?

VuLum made to help you make amazing web app large scale standard requirement for build web app with default admin panel concept for this stuff use Frontend \(ui\) & Backend \(Api\) with design pattern Repository

## **Frontend**

Frontend built with Quasar Framework v.1 latest

> Default component \(encapsulated\)
>
> * Select / Autocomplete
> * Input \(string, number, decimal\)
> * Date Picker \(date time, date, time\)
> * Toggle
> * Text area



default component make you easy to write simply & consistence code and making easily to manage style and behavior of component, so you just edit one affected in all.


## Backend

Backend built with Lumen \(Laravel 8\)

> All of API Module have same pattern and functional, equipped with some traits to manage default method like :
>
> * Get List with format \(default & table\)
> * Find by id
> * Delete by id
> * Restore By id

"**Get List**" method supported shorthand code to allow you set condition to any resources of some module like :

* Searching single & multiple column
* Ordering single & multiple column
* Search Like / Related keyword of some column
* Search Relation \(belongsTo only\) table


so, you can easily manage any resources only with query / url's params although it does not rule out the possibility you create special function to handle what you need

Example :
- get user by gender male including name john
```{host}/users?search=gender:male|name@like:john```


## Authorization Module

* Authentication \(JWT\)
* Permission / Gate \(UI & API\)
* Generator \(all in module\)

## Default Page & Module

* Users \(manage users app\)
* Permissions \(manage permission of role\)
* Roles \( manage role of users\)
* Menu Items \(manage list item of some menu\)
* Menus \(manage menu of users\)



# USAGE: 
1. Composer install
2. npm install inside "ui" folder
3. defined your module in : /generator/list.php
4. execute the file in : /generator/gen.php
   copy the generated file inside /generator/output
5. dont forget register the module in : app/Providers/AppServiceProvider.php
6. run command in root : php artisan migrate --seed
7. for run laravel & quasar you can see the doc in official page
for login, you can see user & password in : /database/factories/UserFactory

---

## Searchable API USage

    - implement "like" sql: ?search=column:value
    - implement "=" sql   : seach=column!:value

    # Operator Slug
    ~ Any             : like
    ~ Date, DateTime  : start,end,ltd,gtd,lted,gted
    ~ Float, Integer  : lt,gt,lte,gte
    ~ Custom          : is_null, is_not_null


    # greater, less with equal integer ----------------
    - greater than        : ?search=column@gt:integerValue
    - greater than equal  : ?search=column@gte:integerValue
    - less than           : ?search=column@lt:integerValue
    - less than equal     : ?search=column@lte:integerValue

    # greater, less with equal date/dateTime format ----------------
    - greater than        : ?search=column@gtd:dateValue 
    - greater than equal  : ?search=column@gted:dateValue
    - less than           : ?search=column@ltd:dateValue 
    - less than equal     : ?search=column@lted:dateValue 

    # date start & end point
    - start               : ?search=column@start:dateValue 
    - end                 : ?search=column@end:dateValue 

    * [Order] :
    - ASCENDING           : ?order=column:asc
    - DESCENDING          : ?order=column:desc

    * [Group] :
    - default             : ?group=column1|column2ifNeed

    * [Misc] :
    - deleted             : ?trash
    - active & deleted    : ?all


    [Rules] :
    # search with relation
    ?search=relationModel.columName=value
    ! makesure relation has mapped in model, in function searchRelations, ex :
      `public function searchRelations() {
          return [
              'relationName' => (new ModelName())->Columns(),
          ];
      }`
    ! if function not define, please define that function bellow function Columns()

