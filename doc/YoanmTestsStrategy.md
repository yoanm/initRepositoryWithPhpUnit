# Yoanm Tests Strategy compliance

> :information_source: **Default command arguments will allow to have PhpUnit tests compliant with [Yoanm Tests strategy](https://github.com/yoanm/Readme/blob/master/strategy/tests/README.md)**


> :warning: **Requires** this [Composer implementation](https://github.com/yoanm/initRepositoryWithComposer)

> :warning: **Requires** [PhpUnitExtended](https://github.com/yoanm/PhpUnitExtended)

 * [Tests strategy rules validated by configuration reference](#rules-validated)
  * [Mandatory](#rules-validated-mandatory)
    * [**Early stop**](#rules-validated-early-stop)
    * [Strict mode](#rules-validated-strict-mode)
      * [**Exit status**](#rules-validated-strict-mode-exit-status)
      * [**Fails if**](#rules-validated-strict-mode-fails-if)
        * [**Php errors**](#rules-validated-strict-mode-fails-if-php-errors)
        * [**Risky tests**](#rules-validated-strict-mode-fails-if-risky-tests)
    * [Risky tests](#rules-validated-risky-tests)
      * [**No output**](#rules-validated-risky-tests-output)
      * [**No globals manipulation**](#rules-validated-risky-tests-manipulate-globals)
      * [**No test that test nothing**](#rules-validated-risky-tests-test-nothing)
    * [Tests isolation](#rules-validated-tests-isolation)
      * [**Globals backup**](#rules-validated-tests-isolation-globals)
    * [Real coverage](#rules-validated-real-coverage)
      * [**No coverage overflow**](#rules-validated-real-coverage-overflow)
      * [**Risky tests does not count in coverage**](#rules-validated-real-coverage-risky-tests)
    * [\<test-suites>](#rules-validated-test-suites)
      * [**Tests Root directory**](#rules-validated-test-suites-tests-root-directory)
      * [**Tests order**](#rules-validated-test-suites-tests-order)
  * [Optional](#optional)
    * [**Test doc - tested class**](#optional-rule-test-doc-tested-class)
    * [**Test doc - tested class dependencies**](#optional-rule-test-doc-tested-class-dependencies)
 * [Configuration reference](#configuration-reference)
  * [Requirements](#configuration-reference-requirements)
 * [Optional config](#optional-config)

<a name="rules-validated"></a>
## [Tests strategy rules](https://github.com/yoanm/Readme/blob/master/strategy/tests/README.md#rules) validated by [configuration reference](#configuration-reference)

<a name="rules-validated-mandatory"></a>
### Mandatory

<a name="rules-validated-early-stop"></a>
#### [Early stop](https://github.com/yoanm/Readme/blob/master/strategy/tests/README.md#rules-early-stop)

* `stopOnError="true"`
* `stopOnFailure="true"`

<a name="rules-validated-strict-mode"></a>
#### [Strict mode](https://github.com/yoanm/Readme/blob/master/strategy/tests/README.md#rules-strict-mode)

<a name="rules-validated-strict-mode-exit-status"></a>
 * [Exit status](https://github.com/yoanm/Readme/blob/master/strategy/tests/README.md#exit-status) : PhpUnit command will return a failed status if a failed or on error test exist
<a name="rules-validated-strict-mode-fails-if"></a>
 * [Fails if](https://github.com/yoanm/Readme/blob/master/strategy/tests/README.md#rules-strict-mode-fails-if)
<a name="rules-validated-strict-mode-fails-if-php-errors"></a>
  * [Php errors](https://github.com/yoanm/Readme/blob/master/strategy/tests/README.md#rules-strict-mode-fails-if-php-errors)

    * `convertErrorsToExceptions="true"`
    * `convertNoticesToExceptions="true"`
    * `convertWarningsToExceptions="true"`
<a name="rules-validated-strict-mode-fails-if-risky-tests"></a>
  * [Risky tests](https://github.com/yoanm/Readme/blob/master/strategy/tests/README.md#rules-strict-mode-fails-if-risky-tests) 
  
    *Requires [YoanmTestsStrategyListener](https://github.com/yoanm/PhpUnitExtended/blob/master/doc/listener/YoanmTestsStrategyListener.md)*

    * [No Output](https://github.com/yoanm/Readme/blob/master/strategy/tests/README.md#rules-risky-tests-output) with `beStrictAboutOutputDuringTests="true"`
    * [No globals manipulation](https://github.com/yoanm/Readme/blob/master/strategy/tests/README.md#rules-risky-tests-manipulate-globals) with `beStrictAboutChangesToGlobalState="true"` and `backupGlobals="true"`
    * [No test that test nothing](https://github.com/yoanm/Readme/blob/master/strategy/tests/README.md#rules-risky-tests-test-nothing) with `beStrictAboutTestsThatDoNotTestAnything="true"`

<a name="rules-validated-risky-tests"></a>
#### [Risky tests](https://github.com/yoanm/Readme/blob/master/strategy/tests/README.md#rules-risky-tests)

*Requires [YoanmTestsStrategyListener](https://github.com/yoanm/PhpUnitExtended/blob/master/doc/listener/YoanmTestsStrategyListener.md)*

<a name="rules-validated-risky-tests-output"></a>
 * [No Output](https://github.com/yoanm/Readme/blob/master/strategy/tests/README.md#rules-risky-tests-output) with `beStrictAboutOutputDuringTests="true"` 
<a name="rules-validated-risky-tests-manipulate-globals"></a>
 * [No globals manipulation](https://github.com/yoanm/Readme/blob/master/strategy/tests/README.md#rules-risky-tests-manipulate-globals) with `beStrictAboutChangesToGlobalState="true"` and `backupGlobals="true"`
<a name="rules-validated-risky-tests-test-nothing"></a>
 * [No test that test nothing](https://github.com/yoanm/Readme/blob/master/strategy/tests/README.md#rules-risky-tests-test-nothing) with `beStrictAboutTestsThatDoNotTestAnything="true"`

<a name="rules-validated-tests-isolation"></a>
#### [Tests isolation](https://github.com/yoanm/Readme/blob/master/strategy/tests/README.md#rules-tests-isolation)
    
<a name="rules-validated-tests-isolation-globals"></a>
 * [Globals backup](https://github.com/yoanm/Readme/blob/master/strategy/tests/README.md#rules-tests-isolation-globals) with `backupGlobals="true"`
  
<a name="rules-validated-real-coverage"></a>
#### [Real coverage](https://github.com/yoanm/Readme/blob/master/strategy/tests/README.md#rules-real-coverage)
    
<a name="rules-validated-real-coverage-overflow"></a>
 * [No coverage overflow](https://github.com/yoanm/Readme/blob/master/strategy/tests/README.md#rules-real-coverage-overflow)
      
  * `forceCoversAnnotation="true"`

  If no annotation, no coverage.

  Symply add the following as test class or test method comment

  ```
  /**
   * @covers FULLY\QUALIFIED\NAMESPACE\TO\MyClass
   */
  ```

<a name="rules-validated-real-coverage-risky-tests"></a>
 * [Risky tests does not count in coverage](https://github.com/yoanm/Readme/blob/master/strategy/tests/README.md#rules-real-coverage-risky-tests)
    
  * [No Output](https://github.com/yoanm/Readme/blob/master/strategy/tests/README.md#rules-risky-tests-output) with `beStrictAboutOutputDuringTests="true"` (requires [YoanmTestsStrategyListener](https://github.com/yoanm/PhpUnitExtended/blob/master/doc/listener/YoanmTestsStrategyListener.md))
  * [No globals manipulation](https://github.com/yoanm/Readme/blob/master/strategy/tests/README.md#rules-risky-tests-manipulate-globals) => already handled by PhpUnit
  * [No test that test nothing](https://github.com/yoanm/Readme/blob/master/strategy/tests/README.md#rules-risky-tests-test-nothing) => already handled by PhpUnit

<a name="rules-validated-test-suites"></a>
#### \<test-suites>
    
<a name="rules-validated-test-suites-tests-root-directory"></a>
  * [Tests Root directory](https://github.com/yoanm/Readme/blob/master/strategy/tests/README.md#tests-root-directory)
<a name="rules-validated-test-suites-tests-order"></a>
  * [Tests order](https://github.com/yoanm/Readme/blob/master/strategy/tests/README.md#tests-order)

### Optional

<a name="optional-rule-test-doc-tested-class"></a>
 * [Test doc - tested class](https://github.com/yoanm/Readme/blob/master/strategy/tests/README.md#rules-test-documentation-tested-class-description) : by using `@covers`
      
   *In fact, required if coverage is used as configuration uses `forceCoversAnnotation="true"`*

<a name="optional-rule-test-doc-tested-class-dependencies"></a>
 * [Test doc - tested class dependencies](https://github.com/yoanm/Readme/blob/master/strategy/tests/README.md#rules-test-documentation-tested-class-dependencies-description) by using `@uses`
  
    * Advice : use `checkForUnintentionallyCoveredCode="true"`, to be sure sure that new dependencies will be forced to be documented. With [configuration reference](#configuration-reference), it will convert test into risky test, and risky test will be converted into failed test by [YoanmTestsStrategyListener](https://github.com/yoanm/PhpUnitExtended/blob/master/doc/listener/YoanmTestsStrategyListener.md)
      
    Simply add the following as test class or test method comment : 
    ```
    /**
     * @uses FULLY\QUALIFIED\NAMESPACE\TO\MyClassDependencyAClass
     * @uses FULLY\QUALIFIED\NAMESPACE\TO\MyClassDependencyBClass
     */
     class MyClassTest extends \PHPUnit_Framework_TestCase
     ```

## Configuration reference
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
  
  forceCoversAnnotation="true"
  
  bootstrap="vendor/autoload.php"
>
  <listeners>
        <listener class="Yoanm\PhpUnitExtended\Listener\YoanmTestsStrategyListener"/>
  </listeners>

  <testsuites>
      <testsuite name="technical">
          <directory>tests/Technical/Unit/*</directory>
          <!-- define (and so, launch) integration tests after unit tests => slower than unit tests -->
          <directory>tests/Technical/Integration/*</directory>
      </testsuite>
      <!-- defined (and so, launch) functional tests after technical tests => slower than technical tests -->
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
  * [PhpUnitExtended](https://github.com/yoanm/PhpUnitExtended) must be configured like describe [there](https://github.com/yoanm/PhpUnitExtended/blob/master/doc/listener/YoanmTestsStrategyListener.md#configuration-reference)
  * `beStrictAboutChangesToGlobalState="true"`requires `backupGlobals="true"` in order to work

## Optional config
  
 * `colors="true"` : Pretty output
 * `processIsolation="true"` : For [test isolation - different process](https://github.com/yoanm/Readme/blob/master/strategy/tests/README.md#rules-tests-isolation-different-process), but it could create edge cases
 
