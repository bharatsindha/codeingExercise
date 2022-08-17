# Coding Exercise Brief: "O Captain! My Captain!" 

Captain LeChuck is the owner of a small sailing boat. Every summer, he uses it to take people scuba diving around the coast of his home and birthplace, Mêlée Island, where rumours about a lost pirate 🏴‍☠️ treasure have recently surfaced. This made Mr. LeChuck's excursions very popular, so he decided to dump offline bookings for a modern, digital solution.

Your mission, should you choose to accept it, is to code a working, barebones API, as a way to showcase the system's ability to validate, store and present valid bookings.

### Toolkit
- The Laravel framework.
- Cache (Filesystem or Redis) based solution for the storage & retrieval of bookings, because the Captain has an inexplicable fear of databases.

### Rules & Limitations
- A booking consists of two elements: a date and a number of guests.
- There's only one excursion per day.
- The maximum number of guests per boat (a.k.a per day) is 8.
- Bookable dates are workdays (Mon-Fri) between the months of June and August. A booking can take place in this or next year's summer season, but no later than that (if it's June 2020, a booking may be made up to August 31st, 2021). 
- Bookings cannot be made on the day of the excursion. 
- A booking can only be made if the entire group of guests (`numOfGuests`) can fit in the boat.

### Endpoints
- **POST** `/bookings/create`: receives `date` (*day/month/year*) and `numOfGuests` (integer). Using query parameters is fine.
- **GET**`/bookings/read`: an array of bookings, where trip dates are keys and number of booked guests are values. Only return dates with bookings.
**Example response data:**
```
[
	'4/7/2022' => 8,
	'12/7/2022' => 3
]
```

### Final Notes
The point of this simple exercise is to allow you to demonstrate your skills in task comprehension, code organization and overall quality. Don't be afraid to have fun with it- just make sure all the bolts are screwed in tight!

Please upload your work to Github under a seperate commit from the base framework code.

Have fun!
