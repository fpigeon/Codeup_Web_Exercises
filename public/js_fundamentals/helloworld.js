var $greeting = 'Hello World';
var sum = 4 + 5,
	difference = 4 - 5,
	product = 4 * 5,
	quotient = 4 / 5;
// alert($greeting);
// alert("It's a " + $greeting + " type of day");

function doSomething(paramOne, paramTwo) {
	paramOne = paramOne + 3;
	paramOne = paramOne + 1;
	paramOne = paramOne * 8;
	return paramOne * paramTwo;
}

var foo = doSomething(3, 2);
console.log(foo);
var bar = doSomething(4, 2);
console.log(bar);