Table reservation system API

1. Run migrations

2. Create restaurants:
POST /api/restaurants
   
   
    {
        "name": "Test-restaurant",
        "table_count": 5,
        "max_people_count": 20
    }


3.Create tables:
POST api/tables


    {
        "name":"first table",
        "restaurant_id": 1,
	    "place_count": 5
    }


4. Reserve table:
POST api/tables/{table_id}/reserve

    
    {
        "reserved_by": {
	        "first_name":"Petras",
			"last_name":"Petraitis",
			"email":"test@test.com",
			"phone":"+37012312300"
		},
		"reserved_date":"2022-10-27",
		"reserved_time":"14:00",
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


5. Tables list api/tables GET
Filters:


    free_tables (boolean)

    restaurant_id

    name (search table name)

    search_reserved_by (searches by email, phone, first and last name)

