# initRepositoryWithPhpUnit

Command to initialize PhpUnit configuration and folders hierarchy.

> :warning: **Rely on** this [Composer implementation](https://github.com/yoanm/initRepositoryWithComposer) for autoloading

  * [Install](#install)
  * [How to](#how-to)
  * [In the box](#in-the-box)
    * [Command line arguments](#in-the-box-command-line-arguments)
    * [Command line options](#in-the-box-command-line-options)
      * [PhpUnit configuration](#in-the-box-command-line-options-phpunit-configuration)
        * [Options](#in-the-box-command-line-options-phpunit-configuration-options)
        * [Listeners](#in-the-box-command-line-options-phpunit-configuration-listeners)
        * [Test suites](#in-the-box-command-line-options-phpunit-configuration-test-suites)
        * [Filter](#in-the-box-command-line-options-phpunit-configuration-filter)
      * [Folder hierarchy](#in-the-box-command-line-options-folder-hierarchy)
      * [Special](#in-the-box-command-line-options-special)
  * [Full PhpUnit configuration](#full-phpunit-configuration)
  * [Contributing](#contributing)

## Install
```bash
composer require --global yoanm/init-repository-with-phpunit
```

## How to

just type the following
```bash
BIN_PATH/initRepositoryWithPhpUnit path/to/project/directory --strategy-compliance default
```

In case you launch the command from the project directory, you can simply use 
```bash
BIN_PATH/initRepositoryWithPhpUnit --strategy-compliance default
```

See below for more information regarding command line options

## In the box

<a name="in-the-box-command-line-arguments"></a>
### Command line arguments

Lonely argument is the project root directory, default value is `.` (directory where the command is launched)

<a name="in-the-box-command-line-options"></a>
### Command line options

<a name="in-the-box-command-line-options-phpunit-configuration"></a>
#### PhpUnit configuration 

  * `--config-file path`
  
    Path where phpunit configuration will be dumped

<a name="in-the-box-command-line-options-phpunit-configuration-options"></a>
##### Options

Following command line options will append related xml attribute under `<phpunit>` node

  * `--stop-on-error [true]|false`

    Will add `stopOnError="true|false"`

  * `--stop-on-failure [true]|false`

    Will add `stopOnFailure="true|false"`

  * `--convert-errors-to-exception [true]|false`

    Will add `convertErrorsToExceptions="true|false"`

  * `--convert-notices-to-exception [true]|false`

    Will add `convertNoticesToExceptions="true|false"`

  * `--convert-warnings-to-exception [true]|false`

    Will add `convertWarningsToExceptions="true|false"`

  * `--be-strict-about-output-during-test [true]|false`

    Will add `beStrictAboutOutputDuringTests="true|false"`

  * `--be-strict-about-tests-that-do-not-test-anything [true]|false`

    Will add `beStrictAboutTestsThatDoNotTestAnything="true|false"`

  * `--be-strict-about-changes-to-global-state [true]|false`

    Will add `beStrictAboutChangesToGlobalState="true|false"`
    
    :information_source: Be aware that `beStrictAboutChangesToGlobalState="true"` requires, at least, either `backupGlobals="true"` or `backupStaticAttributes="true"` in order to do something

  * `--backup-globals [true]|false`

    Will add `backupGlobals="true|false"`

  * `--backup-static-attributes [true]|false`

    Will add `backupStaticAttributes="true|false"`

  * `--check-for-unintentionally-covered-code [true]|false`

    Will add `checkForUnintentionallyCoveredCode="true|false"`

  * `--force-covers-annotation [true]|false`

    Will add `forceCoversAnnotation="true|false"`

  * `--bootstrap bootstrap-path`

    Could be a relative path (root path will be the phpunit configuration file location) or an absolute path

    Will add `bootstrap="bootstrap-path"`
    
    You can also use the `--bootstrap-delegate delegateName` option. 
    
    Current implemented delegates are : 
    
      * `composer` : will be equivalent to `--bootstrap vendor/autoload.php`
      
    :warning: `--bootstrap-delegate` override `--bootstrap`

  * `--process-isolation [true]|false`

    Will add `processIsolation="true|false"`
  
  * `--colors [true]|false`

    Will add `colors="true|false"`

<a name="in-the-box-command-line-options-phpunit-configuration-listeners"></a>
##### Listeners

Following command line options will append related xml node under `<phpunit>` -> `<listeners>` node

  * `--listener-class "Fully\Qualified\Namespace\To\ListenerClass"` *Multiple listeners allowed*

    Will append  following node `<listener class="Fully\Qualified\Namespace\To\ListenerClass"/>`

<a name="in-the-box-command-line-options-phpunit-configuration-test-suites"></a>
##### Test suites

Following command line options will append related xml node under `<phpunit>` -> `<testsuites>` node

*If `suiteName` is not provided, node will be appended under a* **default test suite named "default"**

  * `--test-suite-directory "[suiteName#]path"` *Multiple suite directories allowed*

    Could be a relative path (root path will be the phpunit configuration file location) or an absolute path

    Will append  following node `<directory>path</directory>` under `<testsuite name="suiteName">`

  * `--test-suite-file "[suiteName#]path"` *Multiple suite files allowed*

    Could be a relative path (root path will be the phpunit configuration file location) or an absolute path

    Will append  following node `<file>path</file>` under `<testsuite name="suiteName">`

<a name="in-the-box-command-line-options-phpunit-configuration-filter"></a>
##### Filter

  * `--filter-whitelist-directory path` *Multiple whitelist directories allowed*

    Could be a relative path (root path will be the phpunit configuration file location) or an absolute path

    Will append  following node `<directory>src</directory>` into the `<filter>` -> `<whitelist>` node of phpunit configuration

  * `--filter-whitelist-file path` *Multiple whitelist files allowed*

    Could be a relative path (root path will be the phpunit configuration file location) or an absolute path

    Will append  following node `<file>src</file>` into the `<filter>` -> `<whitelist>` node of phpunit configuration

<a name="in-the-box-command-line-options-folder-hierarchy"></a>
### Folder hierarchy

  * `--test-path "subPath/subSubPath"` *Multiple test directories allowed*
  
    *Path will be appended in the project root directory defined by the command argument*
    
    Will create the given directory hierarchy if not already there.
 
<a name="in-the-box-command-line-options-special"></a>
### Special 

  * `--strategy-compliance name` 
    
    *:warning: When using a strategy, some single value options described above could be overwritten and some multi-values options could have extra data (depends of the strategy choosen).*
    
    *It means you still be able to defined options that strategy does not use*
    
    Available strategies :
    
    * `default` : 

      Will create :
      
      * a `phpunit.xml.dist` config file with a basic configuration
      * default folder hierarchy

      Using this strategy will 

      * Override following single value options
        * `--config-file phpunit.xml.dist`
        * `--colors true`
        * `--bootstrap-delegate composer`
      * Append extra data to following multi-values options
        * `--filter-whitelist-directory "src"`
        * `--test-suite-directory "tests/*"`
        * `--test-path "tests"`

    * `YoanmTestsStrategy` : See [compliance document](./doc/YoanmTestsStrategy.md)
    
      :warning: **Requires** [PhpUnitExtended](https://github.com/yoanm/PhpUnitExtended) 

      Will create :
      
      * a `phpunit.xml.dist` config file with specific configuration to be compliant with the strategy
      * folder hierarchy compliant with the strategy

      Using this strategy will 
        
      * Override following single value options
        * `--stop-on-error true`
        * `--stop-on-failure true`
        * `--convert-errors-to-exception true`
        * `--convert-notices-to-exception true`
        * `--convert-warnings-to-exception true`
        * `--be-strict-about-output-during-test true`
        * `--be-strict-about-tests-that-do-not-test-anything true`
        * `--be-strict-about-changes-to-global-state true`
        * `--force-covers-annotation true`
        * `--backup-globals true`
        * `--config-file phpunit.xml.dist`
        * `--bootstrap-delegate composer`
      * Append extra data to following multi-values options
        * `--filter-whitelist-directory "src"`
        * `--test-suite-directory "technical#tests/Technical/Unit/*"`
        * `--test-suite-directory "technical#tests/Technical/Integration/*"`
        * `--test-suite-directory "functional#tests/Functional/*"` 
        * `--listener-class "Yoanm\PhpUnitExtended\Listener\YoanmTestsStrategyListener"`
        * `--test-path "tests/Technical/Unit"`
        * `--test-path "tests/Technical/Integration"`
        * `--test-path "tests/Functional"`

## Full PhpUnit configuration
```xml
<?xml version="1.0" encoding="UTF-8"?>

<phpunit
  stopOnError="true"
  stopOnFailure="true"
  convertErrorsToExceptions="true"
  convertNoticesToExceptions="true"
  convertWarningsToExceptions="true"
  beStrictAboutOutputDuringTests="true"
  beStrictAboutTestsThatDoNotTestAnything="true"
  beStrictAboutChangesToGlobalState="true"
  backupGlobals="true"
  backupStaticAttributes="true"
  checkForUnintentionallyCoveredCode="true"
  forceCoversAnnotation="true"
  bootstrap="bootstrap-path"
  processIsolation="true"
  colors="true"
>
  <listeners>
        <listener class="Fully\Qualified\Namespace\To\ListenerClass"/>
        <listener class="Fully\Qualified\Namespace\To\SecondListenerClass"/>
  </listeners>

  <testsuites>
      <testsuite name="first-suite">
          <directory>directory_1</directory>
          <directory>directory_2</directory>
          <file>file_1</file>
          <file>file_2</file>
      </testsuite>
      <testsuite name="second-suite">
          <directory>directory_3</directory>
          <directory>directory_4</directory>
          <file>file_3</file>
          <file>file_4</file>
      </testsuite>
  </testsuites>

  <filter>
    <whitelist>
      <directory>directory_5</directory>
      <file>file_5</file>
    </whitelist>
  </filter>
</phpunit>
```
 
## Contributing
See [contributing note](./CONTRIBUTING.md)

