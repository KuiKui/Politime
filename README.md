# Politime

Command line tasks logger, to make your boss happy.

## Principle

- It use a list of topics specified in json file which can be centralized.
- The user select one or more topics on which he worked today.
- Its results are saved in a json file and it can be exposed later, in the proper format.

# Usage

List predefined topics :

```shell
$ politime list topics
```

Add topics on which you worked :

```shell
# Today
$ politime add

# Yesterday
$ politime add yesterday

# Another specific day
$ politime add 2017-10-23
```

To overwrite previously saved topics, use `set` command instead of `add` :

```
# Today
$politime set
```

List saved topics by days :

```shell
$ politime list
```

## Requirement

- PHP 5.5 (accessibility over security)

## Install

Clone the project :

```shell
$ git clone git@github.com:KuiKui/Politime.git /path-to-politime
```

Install dependencies :

```shell
$ cd /path-to-politime
$ composer install
```

The executable is in the `bin/` directory, you can configure your `$PATH` :

```shell
$ export PATH=/path-to-politime/bin:$PATH
```

## Configure topics

Update the `topics.json` file in the `data` directory containing a list of topics (see the [it](data/topics-example.json)).

You can also configure an environment variable to specify a custom path to your topics file :

```shell
$ export POLITIME_TOPICS_FILENAME=/path-to-your-custom-data/my-custom-topics.json
```

It's possible to reach remote file to use centralized topics in a same team :

```shell
$ export POLITIME_TOPICS_FILENAME=http://path-to-your-custom-remote-data/team-remote-topics.json
```

## Configure timeslots

By default, Politime save your activity in a json file `timeslots.json` in the `data` directory.

You can configure an environment variable to specify a custom path to your timeslots file :

```shell
$ export POLITIME_TIMESLOTS_FILENAME=/path-to-your-custom-data/politime-save.json
```
