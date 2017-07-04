# Code Challenge

*Specs*
  + MariaDB 10.1.21
  + PHP 7.1.1

*GET STARTED*

I've included the bundled javascript, but if you have to recompile:
```
npm install
npm start
```

*Cities with stores:*
Canada > Lethbridge
Australia > Woodridge

## Challenges
I have never used Codeigniter before, and it was a challenge learning its ins and outs. 

I found the framework somewhat easy to work with, but I missed a lot of the conveniences I'm used to having with Laravel.

For example, Laravel collections makes for prettier code than using straight php functions like array_map, array_filter, etc.

I also missed Eloquent, Laravel's ORM.

## JavaScript

*Build Tools*
In the interest of time, I didn't put together a very complicated Webpack config. I would have liked to avoid loading ALL of bootstrap for the little bit I used.

*Front End Workflow*
I also much prefer to use a front end framework to handle routing, keeping a tool like Codeigniter for RESTful API calls only. 

Such an approach has many benefits:
- a more productive workflow
- no page refreshes
- you avoid the very verbose jQuery code needed to update the DOM reactively
- code splitting (load only what you need for a particular route)

To compensate a little, I created a very simple module loader in \application\index.js to make sure I don't try and run code that shouldn't be ran.

*jQuery*
This was a challenge. 

I haven't used jQuery in a long time, and I really missed newer tools like Axios and Asynquence for async work.

## Final Thoughts
Most of my work is done with Vue.js, React and Angular, and this entire project could have been done in a fraction of the time if I had a framework to use.

The sheer volume of code I had to write to pull off this simple UI work was exhausting :)