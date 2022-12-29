# PHP Function Chaining
 Experimental PHP Function chaining, far from done. XDebug shows it's footprint is very small, so that's great.

 Further work would enable more internal PHP functions to be used with implicit parameters. Internal PHP function signatures are inconsistent, and automating this is tricky. I believe the best direction would be to manully map out all supported internal functions, their type, and the position of the implicit parameter given the type. I am unsure whether to support multiple implicit parameters, for example, assuming that $callback is the implicit parameter in array_map. Perhaps multiple types could be mappped to function names and indices along with the types. This will be explored at a future time.

 ![Xdebug](https://toxyy.github.io/chain/xdebug.png)
