# php-imdb-ripper #

Extracts movie information from IMDb website. It's very simple and easy to extend.
Also provides links to fullsize images of the poster and cast.

## Requires ##

PHP5

## Usage ##

```php
require_once('imdb-ripper.php');

$imdbRipper = new IMDBRipper();

// Get info from The Matrix
print_r($imdbRipper->main(133093));
```

The above will return an array like this one:

```
Array
(
    [title] => Matrix
    [image] => http://ia.media-imdb.com/images/M/MV5BMjEzNjg1NTg2NV5BMl5BanBnXkFtZTYwNjY3MzQ5._V1jpg
    [original_title] => The Matrix
    [year] => 1999
    [duration] => 136 min
    [score] => 8.7
    [content_rating] => 18
    [director] => Array
        (
            [0] => Array
                (
                    [name] => Andy Wachowski
                    [href] => /name/nm0905152/
                )

            [1] => Array
                (
                    [name] => Lana Wachowski
                    [href] => /name/nm0905154/
                )

        )

    [writers] => Array
        (
            [0] => Array
                (
                    [name] => Andy Wachowski
                    [href] => /name/nm0905152/
                )

            [1] => Array
                (
                    [name] => Lana Wachowski
                    [href] => /name/nm0905154/
                )

        )

    [stars] => Array
        (
            [0] => Array
                (
                    [name] => Keanu Reeves
                    [href] => /name/nm0000206/
                )

            [1] => Array
                (
                    [name] => Laurence Fishburne
                    [href] => /name/nm0000401/
                )

            [2] => Array
                (
                    [name] => Carrie-Anne Moss
                    [href] => /name/nm0005251/
                )

        )

    [genres] => Array
        (
            [0] => Action
            [1] => Adventure
            [2] => Sci-Fi
        )

    [cast] => Array
        (
            [0] => Array
                (
                    [name] => Keanu Reeves
                    [href] => /name/nm0000206/
                    [image] => http://ia.media-imdb.com/images/M/MV5BNjUxNDcwMTg4Ml5BMl5BanBnXkFtZTcwMjU4NDYyOA@@._V1jpg
                )

            [1] => Array
                (
                    [name] => Laurence Fishburne
                    [href] => /name/nm0000401/
                    [image] => http://ia.media-imdb.com/images/M/MV5BMTc0NjczNDc1MV5BMl5BanBnXkFtZTYwMDU0Mjg1._V1jpg
                )

            [2] => Array
                (
                    [name] => Carrie-Anne Moss
                    [href] => /name/nm0005251/
                    [image] => http://ia.media-imdb.com/images/M/MV5BMTYxMjgwNzEwOF5BMl5BanBnXkFtZTcwNTQ0NzI5Ng@@._V1jpg
                )

            [3] => Array
                (
                    [name] => Hugo Weaving
                    [href] => /name/nm0915989/
                    [image] => http://ia.media-imdb.com/images/M/MV5BMjAxMzAyNDQyMF5BMl5BanBnXkFtZTcwOTM4ODcxMw@@._V1jpg
                )

            [4] => Array
                (
                    [name] => Gloria Foster
                    [href] => /name/nm0287825/
                    [image] => http://ia.media-imdb.com/images/M/MV5BMTQxNjY0NjY5M15BMl5BanBnXkFtZTcwNzU3MjkwOA@@._V1jpg
                )

            [5] => Array
                (
                    [name] => Joe Pantoliano
                    [href] => /name/nm0001592/
                    [image] => http://ia.media-imdb.com/images/M/MV5BMTA5NjgwODU4NDZeQTJeQWpwZ15BbWU3MDE0NzUwNDQ@._V1jpg
                )

            [6] => Array
                (
                    [name] => Marcus Chong
                    [href] => /name/nm0159059/
                    [image] => http://ia.media-imdb.com/images/M/MV5BMTMxMTc2NDg1N15BMl5BanBnXkFtZTYwMTkxMTA3._V1jpg
                )

            [7] => Array
                (
                    [name] => Julian Arahanga
                    [href] => /name/nm0032810/
                    [image] => http://ia.media-imdb.com/images/M/MV5BMTI3MTg1NzU3OF5BMl5BanBnXkFtZTYwMDI3OTQ3._V1jpg
                )

            [8] => Array
                (
                    [name] => Matt Doran
                    [href] => /name/nm0233391/
                    [image] => http://ia.media-imdb.com/images/M/MV5BMTY0Mzk0Mjc2M15BMl5BanBnXkFtZTcwNjM3ODU1OA@@._V1jpg
                )

            [9] => Array
                (
                    [name] => Belinda McClory
                    [href] => /name/nm0565883/
                    [image] => http://ia.media-imdb.com/images/M/MV5BMTk3OTcyMDY0OV5BMl5BanBnXkFtZTYwMjgwMTk3._V1jpg
                )

            [10] => Array
                (
                    [name] => Anthony Ray Parker
                    [href] => /name/nm0662562/
                    [image] => http://ia.media-imdb.com/images/M/MV5BOTIxNjk1MDQwNV5BMl5BanBnXkFtZTcwNTQwMjUxOA@@._V1jpg
                )

            [11] => Array
                (
                    [name] => Paul Goddard
                    [href] => /name/nm0323822/
                    [image] => http://ia.media-imdb.com/images/M/MV5BMTc1MTIyMTI5OV5BMl5BanBnXkFtZTcwMTc4OTkwOA@@._V1jpg
                )

            [12] => Array
                (
                    [name] => Robert Taylor
                    [href] => /name/nm0853079/
                    [image] => http://ia.media-imdb.com/images/M/MV5BOTM1NTk5MzA4OF5BMl5BanBnXkFtZTcwMTgwMTA5Nw@@._V1jpg
                )

            [13] => Array
                (
                    [name] => David Aston
                    [href] => /name/nm0040058/
                    [image] => http://ia.media-imdb.com/images/M/MV5BMjA2Njc4Nzk2OV5BMl5BanBnXkFtZTcwMjExODIwOA@@._V1jpg
                )

            [14] => Array
                (
                    [name] => Marc Aden Gray
                    [href] => /name/nm0336802/
                    [image] => http://ia.media-imdb.com/images/M/MV5BMTM5MTE2MjAwOF5BMl5BanBnXkFtZTcwMzc2MzEzOQ@@._V1jpg
                )

        )

)

```

It's a work in progress, if you need a new feature, just send a request.

## To do ##

  * keywords
  * series (chapters)
  * extract movie images
  * full credits
  * more...

Enjoy.
