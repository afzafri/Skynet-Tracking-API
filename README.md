# Skynet Tracking API
- Return JSON formatted string of Skynet Tracking details
- I have been looking for Skynet API but I cannot find the one that actually work and easy to use, so, I developed my own.
- Can be use for tracking the Skynet parcel in your own project/system
- Note:
  - This is not the official API, this is actually just a "hack", or workaround for obtaining the tracking data.
  - This API will fetch data directly from the Skynet Tracking website, so if there are any problem with the site, this API will also affected.

# Installation
```composer require afzafri/skynet-tracking-api:dev-master```

# Usage
- ```http://site.com/api.php?trackingNo=CODE```
- where ```CODE``` is your parcel tracking number
- It will then return a JSON formatted string, you can parse the JSON string and do what you want with it.

# Sample Response
```yaml
{
  "http_code": 200,
  "error_msg": "No error",
  "message": "Record Found",
  "data": [
    {
      "date": "23 Apr 2019 ",
      "time": "5:55PM",
      "process": "Delivered",
      "location": "CENTRAL REGION 2"
    },
    {
      "date": "23 Apr 2019 ",
      "time": "9:09AM",
      "process": "Out For Delivery",
      "location": "CENTRAL REGION 2"
    },
    {
      "date": "19 Apr 2019 ",
      "time": "7:02AM",
      "process": "Arrived Cr2",
      "location": "CENTRAL REGION 2"
    },
    {
      "date": "18 Apr 2019 ",
      "time": "8:32PM",
      "process": "Departed To Hub",
      "location": "Subang Jaya"
    },
    {
      "date": "18 Apr 2019 ",
      "time": "6:56PM",
      "process": "Arrived At Sorting Facility",
      "location": "Subang Jaya"
    }
  ],
  "info": {
    "creator": "Afif Zafri (afzafri)",
    "project_page": "https://github.com/afzafri/Skynet-Tracking-API",
    "date_updated": "15/01/2020"
  }
}
```
# Created By
- Afif Zafri
- Date: 21/12/2016
- Updated At: 15/01/2020
- Contact: http://fb.me/afzafri

# License
This library is under ```MIT license```, please look at the LICENSE file
