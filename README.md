Table reservation system API

Run migrations

Create restaurants:
POST /api/restaurants
   
   
     {
        "name": "Test-restaurant",
        "max_people_count": 20
     }


Create tables:
POST api/tables


     {
        "name":"first table",
        "restaurant_id": 1,
	    "place_count": 5
     }


Reserve table:
POST api/tables/reserve

    
    {
        "reserved_date":"2022-10-27",
	    "reserved_time":"14:00",
        "table_id":1,
        "first_free_table":false,
        "reserved_by": {
	        "first_name":"Petras",
			"last_name":"Petraitis",
			"email":"test@test.com",
			"phone":"+37012312300"
		},
		"users": [
		  {
			"first_name":"Petras",
			"last_name":"Petraitis",
			"email":"test@test.com",
			"phone":"+37012312300"
		  },
		  {
			"first_name":"Jonas",
			"last_name":"Jonaitis",
			"email":"test1@test1.com",
			"phone":"+37012312301"
		  }
		]
    }


Use table_id when you want select custom table to reserve.
Use first_free_table - true if you want select first free table to reserve.

Tables list api/tables GET
Filters:


    free_tables (boolean)

    place_count

    restaurant_id

    name (search table name)

    search_reserved_by (searches by email, phone, first and last name)

