# initRepositoryWithPhpUnit

> *Implementation is compliant to this* **[Tests strategy](https://github.com/yoanm/Readme/blob/master/TESTS_STRATEGY.md)**

Command to initialize PhpUnit configuration and folders hierarchy

 * [Rules validated with configuration reference](#rules-validated)
  * [Mandatory](#mandatory)
    * [**Early stop**](#mandatory-early-stop)
    * [Strict mode](#mandatory-strict-mode)
      * [**Exit status**](#mandatory-strict-mode-exit-status)
      * [**Fails if**](#mandatory-strict-mode-fails-if)
        * [**Php errors**](#mandatory-strict-mode-fails-if-php-errors)
        * [**Risky tests**](#mandatory-strict-mode-fails-if-risky-tests)
    * [Risky tests](#mandatory-risky-tests)
      * [**No output**](#mandatory-risky-tests-output)
      * [**No globals manipulation**](#mandatory-risky-tests-manipulate-globals)
      * [**No test that test nothing**](#mandatory-risky-tests-test-nothing)
    * [Tests isolation](#mandatory-tests-isolation)
      * [**Globals backup**](#mandatory-tests-isolation-globals)
      * [**Static class member backup**](#mandatory-tests-isolation-static-class-member)
    * [Real coverage](#mandatory-real-coverage)
      * [**No coverage overflow**](#mandatory-real-coverage-overflow)
      * [**Risky tests does not count in coverage**](#mandatory-real-coverage-risky-tests)
    * [\<listener>](#listener)
      * [**Strict mode - fails if - risky tests**](#listener-rule-1)
      * [**Real coverage - risky tests  does not count in coverage**](#listener-rule-2)
    * [\<test-suites>](#test-suites)
      * [**Tests Root directory**](#test-suites-tests-root-directory)
      * [**Tests order**](#test-suites-tests-order)
  * [Optional](#optional)
    * [**Test doc - tested class**](#optional-rule-1)
    * [**Test doc - tested class dependencies**](#optional-rule-2)
 * [Optional config](#optional-config)
 * [Configuration reference](#configuration-reference)
 * [Configuration requirements](#configuration-requirements)

<a name="rules-validated"></a>
## [Rules](https://github.com/yoanm/Readme/blob/master/TESTS_STRATEGY.md#rules) validated with [configuration reference](#configuration-reference)

### Mandatory

<a name="mandatory-early-stop"></a>
#### [Early stop](https://github.com/yoanm/Readme/blob/master/TESTS_STRATEGY.md#rules-early-stop)

* `stopOnError="true"`
* `stopOnFailure="true"`

<a name="mandatory-strict-mode"></a>
#### [Strict mode](https://github.com/yoanm/Readme/blob/master/TESTS_STRATEGY.md#rules-strict-mode)

<a name="mandatory-strict-mode-exit-status"></a>
 * [Exit status](https://github.com/yoanm/Readme/blob/master/TESTS_STRATEGY.md#exit-status) : PhpUnit command will return a failed status if a failed or on error test exist
<a name="mandatory-strict-mode-fails-if"></a>
 * [Fails if](https://github.com/yoanm/Readme/blob/master/TESTS_STRATEGY.md#rules-strict-mode-fails-if)
<a name="mandatory-strict-mode-fails-if-php-errors"></a>
  * [Php errors](https://github.com/yoanm/Readme/blob/master/TESTS_STRATEGY.md#rules-strict-mode-fails-if-php-errors)

    * `convertErrorsToExceptions="true"`
    * `convertNoticesToExceptions="true"`
    * `convertWarningsToExceptions="true"`
<a name="mandatory-strict-mode-fails-if-risky-tests"></a>
  * [Risky tests](https://github.com/yoanm/Readme/blob/master/TESTS_STRATEGY.md#rules-strict-mode-fails-if-risky-tests) (requires [`listener`](#listener))

    * [No Output](https://github.com/yoanm/Readme/blob/master/TESTS_STRATEGY.md#rules-risky-tests-output) with `beStrictAboutOutputDuringTests="true"`
    * [No globals manipulation](https://github.com/yoanm/Readme/blob/master/TESTS_STRATEGY.md#rules-risky-tests-manipulate-globals) with `beStrictAboutChangesToGlobalState="true"`
    * [No test that test nothing](https://github.com/yoanm/Readme/blob/master/TESTS_STRATEGY.md#rules-risky-tests-test-nothing) with `beStrictAboutTestsThatDoNotTestAnything="true"`

<a name="mandatory-risky-tests"></a>
#### [Risky tests](https://github.com/yoanm/Readme/blob/master/TESTS_STRATEGY.md#rules-risky-tests)

<a name="mandatory-risky-tests-output"></a>
 * [No Output](https://github.com/yoanm/Readme/blob/master/TESTS_STRATEGY.md#rules-risky-tests-output) with `beStrictAboutOutputDuringTests="true"` 
<a name="mandatory-risky-tests-manipulate-globals"></a>
 * [No globals manipulation](https://github.com/yoanm/Readme/blob/master/TESTS_STRATEGY.md#rules-risky-tests-manipulate-globals) with `beStrictAboutChangesToGlobalState="true"`
<a name="mandatory-risky-tests-test-nothing"></a>
 * [No test that test nothing](https://github.com/yoanm/Readme/blob/master/TESTS_STRATEGY.md#rules-risky-tests-test-nothing) with `beStrictAboutTestsThatDoNotTestAnything="true"`

<a name="mandatory-tests-isolation"></a>
#### [Tests isolation](https://github.com/yoanm/Readme/blob/master/TESTS_STRATEGY.md#rules-tests-isolation)
    
<a name="mandatory-tests-isolation-globals"></a>
 * [Globals backup](https://github.com/yoanm/Readme/blob/master/TESTS_STRATEGY.md#rules-tests-isolation-globals) with `backupGlobals="true"`
      
   *Required by `beStrictAboutChangesToGlobalState="true"`*

<a name="mandatory-tests-isolation-static-class-member"></a>
 * [Static class member backup](https://github.com/yoanm/Readme/blob/master/TESTS_STRATEGY.md#rules-tests-isolation-static-class-member) with `backupStaticAttributes="false"`
  
<a name="mandatory-real-coverage"></a>
#### [Real coverage](https://github.com/yoanm/Readme/blob/master/TESTS_STRATEGY.md#rules-real-coverage)
    
<a name="mandatory-real-coverage-overflow"></a>
 * [No coverage overflow](https://github.com/yoanm/Readme/blob/master/TESTS_STRATEGY.md#rules-real-coverage-overflow)
      
  * `forceCoversAnnotation="true"`

  If no annotation, no coverage.

  Symply add the following as test class or test method comment

  ```
  /**
   * @covers FULLY\QUALIFIED\NAMESPACE\TO\MyClass
   */
  ```

<a name="mandatory-real-coverage-risky-tests"></a>
 * [Risky tests does not count in coverage](https://github.com/yoanm/Readme/blob/master/TESTS_STRATEGY.md#rules-real-coverage-risky-tests)
    
  * [No Output](https://github.com/yoanm/Readme/blob/master/TESTS_STRATEGY.md#rules-risky-tests-output) with `beStrictAboutOutputDuringTests="true"` (requires [`listener`](#listener))
  * [No globals manipulation](https://github.com/yoanm/Readme/blob/master/TESTS_STRATEGY.md#rules-risky-tests-manipulate-globals) with `beStrictAboutChangesToGlobalState="true"`
  * [No test that test nothing](https://github.com/yoanm/Readme/blob/master/TESTS_STRATEGY.md#rules-risky-tests-test-nothing) with `beStrictAboutTestsThatDoNotTestAnything="true"`

<a name="listener"></a>
#### \<listener> (See [TestsStrategyListener](../src/PhpUnit/TestsStrategyListener.php))
      
Listener will validate following mandatory rules

<a name="listener-rule-1"></a>
 * [Strict mode - fails if - risky tests](https://github.com/yoanm/Readme/blob/master/TESTS_STRATEGY.md#rules-strict-mode-fails-if-risky-tests)

  * Required by 
      
    * `beStrictAboutOutputDuringTests="true"` ([No Output](https://github.com/yoanm/Readme/blob/master/TESTS_STRATEGY.md#rules-risky-tests-output))
    * `beStrictAboutChangesToGlobalState="true"` ([No globals manipulation](https://github.com/yoanm/Readme/blob/master/TESTS_STRATEGY.md#rules-risky-tests-manipulate-globals))
    * `beStrictAboutTestsThatDoNotTestAnything="true"` ([No test that test nothing](https://github.com/yoanm/Readme/blob/master/TESTS_STRATEGY.md#rules-risky-tests-test-nothing))

<a name="listener-rule-2"></a>
 * [Real coverage - risky tests  does not count in coverage](https://github.com/yoanm/Readme/blob/master/TESTS_STRATEGY.md#rules-real-coverage-risky-tests) for some specific kinds of risky test   
      
  * Required by `beStrictAboutOutputDuringTests="true"` ([No Output](https://github.com/yoanm/Readme/blob/master/TESTS_STRATEGY.md#rules-risky-tests-output))
 
<a name="test-suites"></a>
#### \<test-suites>
    
<a name="test-suites-tests-root-directory"></a>
  * [Tests Root directory](https://github.com/yoanm/Readme/blob/master/TESTS_STRATEGY.md#tests-root-directory)
<a name="test-suites-tests-order"></a>
  * [Tests order](https://github.com/yoanm/Readme/blob/master/TESTS_STRATEGY.md#tests-order)

### Optional

<a name="optional-rule-1"></a>
 * [Test doc - tested class](https://github.com/yoanm/Readme/blob/master/TESTS_STRATEGY.md#rules-test-documentation-tested-class-description) : by using `@covers`
      
   *In fact, required if coverage is used as configuration uses `forceCoversAnnotation="true"`*

<a name="optional-rule-2"></a>
 * [Test doc - tested class dependencies](https://github.com/yoanm/Readme/blob/master/TESTS_STRATEGY.md#rules-test-documentation-tested-class-dependencies-description) by using `@uses`
  
    * Also use `checkForUnintentionallyCoveredCode="true"`, to be sure sure that new dependencies will be forced to be documented. With [configuration reference](#configuration-reference), it will convert test into risky test, and risky test will be converted into failed test by [`listener`](#listener)
      
    Simply add the following as test class or test method comment : 
    ```
    /**
     * @uses FULLY\QUALIFIED\NAMESPACE\TO\MyClassDependencyAClass
     * @uses FULLY\QUALIFIED\NAMESPACE\TO\MyClassDependencyBClass
     */
     class MyClassTest extends \PHPUnit_Framework_TestCase
     ```

## Optional config
  
 * `bootstrap="vendor/autoload.php"` : Autoload file
 * `colors="true"` : Pretty output
 * `processIsolation="true"` : For [test isolation - different process](https://github.com/yoanm/Readme/blob/master/TESTS_STRATEGY.md#rules-tests-isolation-different-process), but it could create edge cases
 
## Configuration reference
```xml
<phpunit xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
  xsi:noNamespaceSchemaLocation="http://schema.phpunit.de/4.8/phpunit.xsd"
  stopOnError="true"
  stopOnFailure="true"
  convertErrorsToExceptions="true"
  convertNoticesToExceptions="true"
  convertWarningsToExceptions="true"
  beStrictAboutOutputDuringTests="true"
  beStrictAboutChangesToGlobalState="true"
  beStrictAboutTestsThatDoNotTestAnything="true"
  backupGlobals="true"
  backupStaticAttributes="false"
  forceCoversAnnotation="true"
  checkForUnintentionallyCoveredCode="true"
  bootstrap="vendor/autoload.php"
  colors="true"
>
  <listeners>
        <listener class="Yoanm\InitPhpRepositoryTestsStrategy\PhpUnit\TestsStrategyListener"/>
  </listeners>

  <testsuites>
      <testsuite name="technical">
          <directory>tests/Technical/Unit/*</directory>
          <!-- define (and so, launch) integration tests after unit tests => slower than unit tests -->
          <directory>tests/Technical/Integration/*</directory>
      </testsuite>
      <!-- defined (and so, launch) functional tests tests after technical tests => slower than technical tests -->
      <testsuite name="functional">
          <directory>tests/Functional/*</directory>
      </testsuite>
  </testsuites>

  <filter>
    <whitelist>
      <directory>src</directory>
    </whitelist>
  </filter>
</phpunit>
```
### Configuration requirements

  * `beStrictAboutChangesToGlobalState="true"`requires `backupGlobals="true"` in order to work
