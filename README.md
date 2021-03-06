# wpxvuelos

A WordPress implementation of xvuelos

## Demo

https://wp.xvuelos.com

## Requirements
- `node >= 8`
- `php >= 7`
- `environment variables`
  - `SKYSCANNER_URL`
  - `SKYSCANNER_API_KEY`

## Installation

```sh
$ git clone git+https://github.com/facutk/wpxvuelos.git
$ cd wpxvuelos
```

## Local Development

```sh
$ npm start
```

If `node` is not installed
```sh
$ php -t wp -S localhost:8080
```

## Deploy

```sh
$ git add .
$ git commit -m 'changes'
$ git push origin master
```

Merges to `master` branch triggers a deploy in *heroku*.
https://dashboard.heroku.com/apps/wpxvuelos

## Writing Content

### Requirements
- `wp cli`
- `postmark`

#### Installation

```sh
$ brew install wp-cli
$ php -d memory_limit=512M "$(which wp)" package install dirtsimple/postmark
```

##### UUID gen

```sh
$ cd wpxvuelos/wp
$ wp postmark uuid --path="${pwd}"
```

Outputs
```sh
> urn:uuid:e423ab0f-a668-4c48-bdff-2090e66bfca2
```

### Markdown to drafts

```sh
$ wp --path="${pwd}" --debug=postmark postmark sync posts/washington.md
$ wp postmark tree posts/ --path="${pwd}" --debug=postmark
```
