Wordpress shortcode date operations 

### this_current
`[v_day param="this_current"]` => print current date (eg: 15-12-2021)   
`[v_day param="this_current, all"]` => print current date (eg: 15-12-2021)  
`[v_day param="this_current, year"]` => print current year (eg: 2021)  
`[v_day param="this_current, month"]` => print current month (eg: December)  
`[v_day param="this_current, day"]` => print current day (eg: Wesnesday)  


### countdown and s_countdown
<strong>start countdown from specified date<strong>  
`[v_day param="countdown, 2021"]`  
`[v_day param="countdown, 2021-12"]`  
`[v_day param="countdown, 2021-12-30"]`  
`[v_day param="countdown, 2021-12-30 23"]`  
`[v_day param="countdown, 2021-12-30 23:30"]`  
  
`[v_day param="countdown, 12-2021"]`  
`[v_day param="countdown, 30-12-2021"]`  
`[v_day param="countdown, 30-12-2021 23"]`  
`[v_day param="countdown, 30-12-2021 23:30"]`  
  
`[v_day param="countdown, 12-30-2021"]`  
`[v_day param="countdown, 12-30-2021 23"]`  
`[v_day param="countdown, 12-30-2021 23:30"]`  
  
### count_message
`[v_day param="count_message, 30" message="Wellcome"]` =>  Message to be displayed after 30 seconds  

