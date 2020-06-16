/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import '../css/app.scss';

//load the jQuery and import the function from jQuery
const $ = require('jquery');

//load the JS bootstrap part - note that bootstrap doesn't export anything
require('bootstrap');

// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
//import $ from 'jquery';

console.log('Hello Webpack Encore! Edit me in assets/js/app.js');


if(jQuery){
    console.log("jQuery est chargé");
}
else{
    console.log("jQuery n'est pas chargé");
}

let rater=document.getElementById("comment_rate");
let star1 = document.getElementById("rate-1");
let star2 = document.getElementById("rate-2");
let star3 = document.getElementById("rate-3");
let star4 = document.getElementById("rate-4");
let star5 = document.getElementById("rate-5");

star1.addEventListener("click", function(event){
    console.log('1 clicked');
    rater.value='1';
    star1.className="fa fa-star";
    star2.className="fa fa-star-o";
    star3.className="fa fa-star-o";
    star4.className="fa fa-star-o";
    star5.className="fa fa-star-o";
});


star2.addEventListener("click", function(event){
    console.log('2 clicked');
    rater.value='2';
    star1.className="fa fa-star";
    star2.className="fa fa-star";
    star3.className="fa fa-star-o";
    star4.className="fa fa-star-o";
    star5.className="fa fa-star-o";
});


star3.addEventListener("click", function(event){
    console.log('3 clicked');
    rater.value='3';
    star1.className="fa fa-star";
    star2.className="fa fa-star";
    star3.className="fa fa-star";
    star4.className="fa fa-star-o";
    star5.className="fa fa-star-o";
});


star4.addEventListener("click", function(event){
    console.log('4 clicked');
    rater.value='4';
    star1.className="fa fa-star";
    star2.className="fa fa-star";
    star3.className="fa fa-star";
    star4.className="fa fa-star";
    star5.className="fa fa-star-o";
});


star5.addEventListener("click", function(event){
    console.log('5 clicked');
    rater.value='5';
    star1.className="fa fa-star";
    star2.className="fa fa-star";
    star3.className="fa fa-star";
    star4.className="fa fa-star";
    star5.className="fa fa-star";
});
