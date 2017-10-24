# Politime

Command line tasks logger, to make your boss happy.

## Principle

- It use a list of topics specified in json file which can be centralized.
- The user select one or more topics on which he worked today.
- Its results are saved in a json file and it can be exposed later, in the proper format.

# Usage

List topics :

```
$ politime list topics
```

Add topics on which you worked :

```
# Today
$ politime add

# Yesterday
$ politime add yesterday

# Another specific day
$ politime add 2017-10-23
```

Add topics for another day :

```
$ politime add 2017-10-27
```

List saved topics by days :

```
$ politime list timeslots
```

## Requirement

- PHP 5.5 (accessibility over security)

## Install

Clone the project :

```
$ git clone git@github.com:KuiKui/Politime.git /path-to-politime
```

Install dependencies :

```
$ cd /path-to-politime
$ composer install
```

The executable is in the `bin/` directory, you can configure your `$PATH` :

```
$ export PATH=/path-to-politime/bin:$PATH
```

## Configure topics

Create a `topics.json` file in the `data` directory containing a list of topics (see the [example](data/topics-example.json)).

You can also configure an environment variable to specify a custom path to your topics file :

```
$ export POLITIME_TOPICS_FILENAME=/path-to-your-custom-data/my-custom-topics.json
```

It's possible to reach remote file to use centralized topics in a same team :

```
$ export POLITIME_TOPICS_FILENAME=http://path-to-your-custom-remote-data/team-remote-topics.json
```
