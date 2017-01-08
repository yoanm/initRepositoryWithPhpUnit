# initRepositoryWithPhpUnit

Command to initialize PhpUnit configuration and folders hierarchy.

> :information_source: **[Yoanm Tests strategy](https://github.com/yoanm/Readme/blob/master/TESTS_STRATEGY.md) compliant**


> :warning: **Requires** this [Composer implementation](https://github.com/yoanm/initRepositoryWithComposer)

 * [Configuration reference](#configuration-reference)
  * [Requirements](#configuration-reference-requirements)
  * [\<listener>](#configuration-reference-listener)
 * [Tests strategy rules validated by configuration reference](#rules-validated)
  * [Mandatory](#rules-validated-mandatory)
    * [**Early stop**](#rules-validated-mandatory-early-stop)
    * [Strict mode](#rules-validated-mandatory-strict-mode)
      * [**Exit status**](#rules-validated-mandatory-strict-mode-exit-status)
      * [**Fails if**](#rules-validated-mandatory-strict-mode-fails-if)
        * [**Php errors**](#rules-validated-mandatory-strict-mode-fails-if-php-errors)
        * [**Risky tests**](#rules-validated-mandatory-strict-mode-fails-if-risky-tests)
    * [Risky tests](#rules-validated-mandatory-risky-tests)
      * [**No output**](#rules-validated-mandatory-risky-tests-output)
      * [**No globals manipulation**](#rules-validated-mandatory-risky-tests-manipulate-globals)
      * [**No test that test nothing**](#rules-validated-mandatory-risky-tests-test-nothing)
    * [Tests isolation](#rules-validated-mandatory-tests-isolation)
      * [**Globals backup**](#rules-validated-mandatory-tests-isolation-globals)
      * [**Static class member backup**](#rules-validated-mandatory-tests-isolation-static-class-member)
    * [Real coverage](#rules-validated-mandatory-real-coverage)
      * [**No coverage overflow**](#rules-validated-mandatory-real-coverage-overflow)
      * [**Risky tests does not count in coverage**](#rules-validated-mandatory-real-coverage-risky-tests)
    * [\<test-suites>](#rules-validated-mandatory-test-suites)
      * [**Tests Root directory**](#rules-validated-mandatory-test-suites-tests-root-directory)
      * [**Tests order**](#rules-validated-mandatory-test-suites-tests-order)
  * [Optional](#optional)
    * [**Test doc - tested class**](#optional-rule-1)
    * [**Test doc - tested class dependencies**](#optional-rule-2)
 * [Optional config](#optional-config)

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
        <listener class="Yoanm\PhpUnitExtended\Listener\TestsStrategyListener"/>
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
<a name="configuration-reference-requirements"></a>
### Requirements

  * `beStrictAboutChangesToGlobalState="true"`requires `backupGlobals="true"` in order to work

<a name="configuration-reference-listener"></a>
### \<listener>

*See [PhpUnitExtended](https://github.com/yoanm/PhpUnitExtended)*
      
Listener is required by
 * `beStrictAboutOutputDuringTests="true"`
 * `beStrictAboutChangesToGlobalState="true"`
 * `beStrictAboutTestsThatDoNotTestAnything="true"`

<a name="rules-validated"></a>
## [Tests strategy rules](https://github.com/yoanm/Readme/blob/master/TESTS_STRATEGY.md#rules) validated by [configuration reference](#configuration-reference)

<a name="rules-validated-mandatory"></a>
### Mandatory

<a name="rules-validated-mandatory-early-stop"></a>
#### [Early stop](https://github.com/yoanm/Readme/blob/master/TESTS_STRATEGY.md#rules-early-stop)

* `stopOnError="true"`
* `stopOnFailure="true"`

<a name="rules-validated-mandatory-strict-mode"></a>
#### [Strict mode](https://github.com/yoanm/Readme/blob/master/TESTS_STRATEGY.md#rules-strict-mode)

<a name="rules-validated-mandatory-strict-mode-exit-status"></a>
 * [Exit status](https://github.com/yoanm/Readme/blob/master/TESTS_STRATEGY.md#exit-status) : PhpUnit command will return a failed status if a failed or on error test exist
<a name="rules-validated-mandatory-strict-mode-fails-if"></a>
 * [Fails if](https://github.com/yoanm/Readme/blob/master/TESTS_STRATEGY.md#rules-strict-mode-fails-if)
<a name="rules-validated-mandatory-strict-mode-fails-if-php-errors"></a>
  * [Php errors](https://github.com/yoanm/Readme/blob/master/TESTS_STRATEGY.md#rules-strict-mode-fails-if-php-errors)

    * `convertErrorsToExceptions="true"`
    * `convertNoticesToExceptions="true"`
    * `convertWarningsToExceptions="true"`
<a name="rules-validated-mandatory-strict-mode-fails-if-risky-tests"></a>
  * [Risky tests](https://github.com/yoanm/Readme/blob/master/TESTS_STRATEGY.md#rules-strict-mode-fails-if-risky-tests) (requires [`listener`](#configuration-reference-listener))

    * [No Output](https://github.com/yoanm/Readme/blob/master/TESTS_STRATEGY.md#rules-risky-tests-output) with `beStrictAboutOutputDuringTests="true"`
    * [No globals manipulation](https://github.com/yoanm/Readme/blob/master/TESTS_STRATEGY.md#rules-risky-tests-manipulate-globals) with `beStrictAboutChangesToGlobalState="true"`
    * [No test that test nothing](https://github.com/yoanm/Readme/blob/master/TESTS_STRATEGY.md#rules-risky-tests-test-nothing) with `beStrictAboutTestsThatDoNotTestAnything="true"`

<a name="rules-validated-mandatory-risky-tests"></a>
#### [Risky tests](https://github.com/yoanm/Readme/blob/master/TESTS_STRATEGY.md#rules-risky-tests)

<a name="rules-validated-mandatory-risky-tests-output"></a>
 * [No Output](https://github.com/yoanm/Readme/blob/master/TESTS_STRATEGY.md#rules-risky-tests-output) with `beStrictAboutOutputDuringTests="true"` 
<a name="rules-validated-mandatory-risky-tests-manipulate-globals"></a>
 * [No globals manipulation](https://github.com/yoanm/Readme/blob/master/TESTS_STRATEGY.md#rules-risky-tests-manipulate-globals) with `beStrictAboutChangesToGlobalState="true"`
<a name="rules-validated-mandatory-risky-tests-test-nothing"></a>
 * [No test that test nothing](https://github.com/yoanm/Readme/blob/master/TESTS_STRATEGY.md#rules-risky-tests-test-nothing) with `beStrictAboutTestsThatDoNotTestAnything="true"`

<a name="rules-validated-mandatory-tests-isolation"></a>
#### [Tests isolation](https://github.com/yoanm/Readme/blob/master/TESTS_STRATEGY.md#rules-tests-isolation)
    
<a name="rules-validated-mandatory-tests-isolation-globals"></a>
 * [Globals backup](https://github.com/yoanm/Readme/blob/master/TESTS_STRATEGY.md#rules-tests-isolation-globals) with `backupGlobals="true"`
      
   *Required by `beStrictAboutChangesToGlobalState="true"`*

<a name="rules-validated-mandatory-tests-isolation-static-class-member"></a>
 * [Static class member backup](https://github.com/yoanm/Readme/blob/master/TESTS_STRATEGY.md#rules-tests-isolation-static-class-member) with `backupStaticAttributes="false"`
  
<a name="rules-validated-mandatory-real-coverage"></a>
#### [Real coverage](https://github.com/yoanm/Readme/blob/master/TESTS_STRATEGY.md#rules-real-coverage)
    
<a name="rules-validated-mandatory-real-coverage-overflow"></a>
 * [No coverage overflow](https://github.com/yoanm/Readme/blob/master/TESTS_STRATEGY.md#rules-real-coverage-overflow)
      
  * `forceCoversAnnotation="true"`

  If no annotation, no coverage.

  Symply add the following as test class or test method comment

  ```
  /**
   * @covers FULLY\QUALIFIED\NAMESPACE\TO\MyClass
   */
  ```

<a name="rules-validated-mandatory-real-coverage-risky-tests"></a>
 * [Risky tests does not count in coverage](https://github.com/yoanm/Readme/blob/master/TESTS_STRATEGY.md#rules-real-coverage-risky-tests)
    
  * [No Output](https://github.com/yoanm/Readme/blob/master/TESTS_STRATEGY.md#rules-risky-tests-output) with `beStrictAboutOutputDuringTests="true"` (requires [`listener`](#configuration-reference-listener))
  * [No globals manipulation](https://github.com/yoanm/Readme/blob/master/TESTS_STRATEGY.md#rules-risky-tests-manipulate-globals) with `beStrictAboutChangesToGlobalState="true"`
  * [No test that test nothing](https://github.com/yoanm/Readme/blob/master/TESTS_STRATEGY.md#rules-risky-tests-test-nothing) with `beStrictAboutTestsThatDoNotTestAnything="true"`

<a name="rules-validated-mandatory-test-suites"></a>
#### \<test-suites>
    
<a name="rules-validated-mandatory-test-suites-tests-root-directory"></a>
  * [Tests Root directory](https://github.com/yoanm/Readme/blob/master/TESTS_STRATEGY.md#tests-root-directory)
<a name="rules-validated-mandatory-test-suites-tests-order"></a>
  * [Tests order](https://github.com/yoanm/Readme/blob/master/TESTS_STRATEGY.md#tests-order)

### Optional

<a name="optional-rule-1"></a>
 * [Test doc - tested class](https://github.com/yoanm/Readme/blob/master/TESTS_STRATEGY.md#rules-test-documentation-tested-class-description) : by using `@covers`
      
   *In fact, required if coverage is used as configuration uses `forceCoversAnnotation="true"`*

<a name="optional-rule-2"></a>
 * [Test doc - tested class dependencies](https://github.com/yoanm/Readme/blob/master/TESTS_STRATEGY.md#rules-test-documentation-tested-class-dependencies-description) by using `@uses`
  
    * Also use `checkForUnintentionallyCoveredCode="true"`, to be sure sure that new dependencies will be forced to be documented. With [configuration reference](#configuration-reference), it will convert test into risky test, and risky test will be converted into failed test by [`listener`](#configuration-reference-listener)
      
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
 
