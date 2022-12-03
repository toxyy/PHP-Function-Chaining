<?php

# Copyright (C) 2022 toxyy
#
# Licensed under the Apache License, Version 2.0 (the "License");
# you may not use this file except in compliance with the License.
# You may obtain a copy of the License at
#
#      http://www.apache.org/licenses/LICENSE-2.0
#
# Unless required by applicable law or agreed to in writing, software
# distributed under the License is distributed on an "AS IS" BASIS,
# WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
# See the License for the specific language governing permissions and
# limitations under the License.

class Chain
{
	public function __construct(public &$data = NULL) {}
    public function __call($name, $args = NULL) {
    	if(is_callable($name)) $this->data = $args ? $name(...$args) : $name($this->data);
    	return $this;
    }
    public static function __callStatic($name, $args) { return (new static)->__call($name, $args); }
    public function __set($name, $value) { $this->data = $value; return $this; }
	public static function new(&$name = NULL, $value = NULL) {
		return ($self = new static)->__set($self->data = &$name, $value ?? $name);
	}
    public function __invoke($name = NULL, ...$args) {
		$this->data = $name;
    	// getter
    	if(is_null($name)) return $this->data;
    	// closures and closures returning generators
    	foreach([$name] + $args as $function)
			if(is_callable($function))
				if(($this->data = $function()) instanceof \Generator)
					$this->data = $this->data->next();

		return $this;
    }
    public function __get($name) { return $this; }
    public function __toString() { return $this->data; }
	public function print_r($args) {
		print_r($args);
		return $this;
	}
    public function print($args = NULL) {
    	print $args ?: $this->data; 
		return $this;
    }
    public function echo($args = NULL) {
    	echo $args ?: $this->data;
    	return $this;
    }
}
$chain = function(&$x, $y) { return Chain::new($x, $y); };

// with internal aliases
Chain::new($arr, range(1, 16))
	->array_map(fn($x) =>
		($x % 15 === 0) ? 'fizzbuzz'
		: (($x % 3 === 0) ? 'fizz'
		: (($x % 5 === 0) ? 'buzz'
		: $x)), $arr)
		(fn() => ['add_to_front'] + $arr)
	->implode()
	->print()
	->echo('<br>');

// with calls
Chain::new($arr, range(1, 16))
	(array_map(fn($x) =>
		($x % 15 === 0) ? 'fizzbuzz'
		: (($x % 3 === 0) ? 'fizz'
		: (($x % 5 === 0) ? 'buzz'
		: $x)), $arr))
		//(fn() => ['add_to_front'] + $arr)
	(implode($arr))
	(print($arr))
	->new($ar2, 123)
	(print($ar2))
	(print('<br>'));

// string concatenation
echo <<<HTML
<br>
This is a fizzbuzz chain:
{$chain($arr, range(1, 16))
	->array_map(fn($x) =>
		($x % 15 === 0) ? 'fizzbuzz'
		: (($x % 3 === 0) ? 'fizz'
		: (($x % 5 === 0) ? 'buzz'
		: $x)), $arr)
		(fn() => ['add_to_front'] + $arr)
	->implode()}
HTML;


// function a($test) {
// 	return $test += 1;
// }

// function b($test) {
// 	return $test += 2;
// }

// Chain::new($c)->a(b())->print();
// ->array_map(fn($x) => ($x % 5) ?: 'fizz', array_keys($arr));
// echo $c . PHP_EOL;										// 2
// echo Chain::new($c)->a()->b() . PHP_EOL;	// 5
// (new Chain)->a()->b()->{$g = 6}->{(fn() => $g = 55)()}->data += 1;
// echo $c . $d . PHP_EOL . $g;