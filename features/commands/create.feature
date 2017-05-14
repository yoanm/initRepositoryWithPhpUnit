Feature: As user
  In order to create phpunit configuration file
  I should be able to use the command line

  Scenario: No options
    Given I execute phpunitcm create
    Then an exception must have been thrown

  Scenario: Specify location
    Given I have the folder "test"
    When I execute phpunitcm create with "test" and following options:
    """
    --config-attr "attr1##true"
    """
    Then I should have a configuration file at "test"

  Scenario: Specify Attributes
    Given I execute phpunitcm create with following options:
    """
    --config-attr "attr1##true"
    --config-attr "attr2##plip"
    --config-attr "test##./path"
    --php "ini##plip##plop"
    """
    Then configuration file should contains:
    """
    <?xml version="1.0" encoding="UTF-8"?>
    <phpunit xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
      xsi:noNamespaceSchemaLocation="http://schema.phpunit.de/4.5/phpunit.xsd"
      attr1="true"
      attr2="plip"
      test="./path"
    >
    """

  Scenario: Specify Test suites item
    Given I execute phpunitcm create with following options:
    """
    --test-suite-file "suite1##file11"
    --test-suite-file "suite2##file21"
    --test-suite-file "suite2##file22"
    --test-suite-file "suite1##file12##suffix##Test.php##phpVersion##5.3.0##phpVersionOperator##>="
    --test-suite-directory "suite1##directory11"
    --test-suite-directory "suite2##directory21##phpVersion##5.3.0##phpVersionOperator##>="
    --test-suite-directory "suite2##directory22"
    --test-suite-directory "suite1##directory12"
    --test-suite-excluded "suite1##excluded11"
    --test-suite-excluded "suite2##excluded21"
    --test-suite-excluded "suite2##excluded22"
    --test-suite-excluded "suite1##excluded12"
    """
    Then configuration file should contains:
    """
      <testsuites>
        <testsuite name="suite1">
          <file>file11</file>
          <file suffix="Test.php" phpVersion="5.3.0" phpVersionOperator="&gt;=">file12</file>
          <directory>directory11</directory>
          <directory>directory12</directory>
          <exclude>excluded11</exclude>
          <exclude>excluded12</exclude>
        </testsuite>
        <testsuite name="suite2">
          <file>file21</file>
          <file>file22</file>
          <directory phpVersion="5.3.0" phpVersionOperator="&gt;=">directory21</directory>
          <directory>directory22</directory>
          <exclude>excluded21</exclude>
          <exclude>excluded22</exclude>
        </testsuite>
      </testsuites>
    """

  Scenario: Specify Groups item
    Given I execute phpunitcm create with following options:
    """
    --group-include group1
    --group-exclude group2
    --group-include group3
    --group-exclude group4
    """
    Then configuration file should contains:
    """
      <groups>
        <exclude>
          <group>group2</group>
          <group>group4</group>
        </exclude>
        <include>
          <group>group1</group>
          <group>group3</group>
        </include>
      </groups>
    """

  Scenario: Specify Filter item
    Given I execute phpunitcm create with following options:
    """
    --filter-whitelist-file path1
    --filter-whitelist-file path2
    --filter-whitelist-directory path3
    --filter-whitelist-directory path4
    --filter-whitelist-excluded-file path5
    --filter-whitelist-excluded-file path6
    --filter-whitelist-excluded-directory path7
    --filter-whitelist-excluded-directory path8
    """
    Then configuration file should contains:
    """
      <filter>
        <whitelist>
          <directory>path3</directory>
          <directory>path4</directory>
          <file>path1</file>
          <file>path2</file>
          <exclude>
            <file>path5</file>
            <file>path6</file>
            <directory>path7</directory>
            <directory>path8</directory>
          </exclude>
        </whitelist>
      </filter>
    """

  Scenario: Specify Loggging item
    Given I execute phpunitcm create with following options:
    """
    --log "coverage-html##/tmp/report##lowUpperBound##35##highLowerBound##70"
    --log "coverage-clover##/tmp/coverage.xml"
    """
    Then configuration file should contains:
    """
      <logging>
        <log type="coverage-html" target="/tmp/report" lowUpperBound="35" highLowerBound="70"/>
        <log type="coverage-clover" target="/tmp/coverage.xml"/>
      </logging>
    """

  Scenario: Specify Listeners item
    Given I execute phpunitcm create with following options:
    """
    --listener "Class1"
    --listener "Class2##File2"
    """
    Then configuration file should contains:
    """
      <listeners>
        <listener class="Class1"/>
        <listener class="Class2" file="File2"/>
      </listeners>
    """

  Scenario: Specify Php item
    Given I execute phpunitcm create with following options:
    """
    --php "includePath##."
    --php "ini##foo##bar"
    --php "server##test##foo##bar"
    """
    Then configuration file should contains:
    """
      <php>
        <includePath>.</includePath>
        <ini name="foo" value="bar"/>
        <server name="foo" value="bar">test</server>
      </php>
    """
