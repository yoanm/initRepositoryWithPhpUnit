Feature: As user
  In order to update composer configuration file
  I should be able to use the "update" command line

  Scenario: Specify location
    Given I have the folder "./test"
    When I execute composercm update with "./test" and following options:
    """
    --package-name "pk_namespace2\\pk_name2"
    """
    Then I should have a configuration file at "./test"
    And configuration file at "./test" should contains:
    """
    {
      "name": "pk_namespace2\\pk_name2",
      "type": "default-type",
      "license": "default-license",
      "version": "default-version",
      "description": "default-description"
    }
    """

  Scenario: Basic configuration file
    Given I execute composercm update with following options:
    """
    --package-name "pk_namespace2\\pk_name2" --description "pk description" --type my_type --license my_license --package-version 1.2.3
    """
    Then configuration file key order should be:
    """
    [
      "require",
      "scripts",
      "name",
      "support",
      "bin",
      "description",
      "authors",
      "require-dev",
      "provide",
      "autoload-dev",
      "suggest",
      "version",
      "autoload",
      "type",
      "license",
      "keywords"
    ]
    """
    And configuration file should contains:
    """
    {
      "name": "pk_namespace2\\pk_name2",
      "type": "my_type",
      "license": "my_license",
      "version": "1.2.3",
      "description": "pk description"
    }
    """

  Scenario: Full configuration file
    Given I execute composercm update with following options:
    """
    --package-name "pk_namespace2\\pk_name2" --description "pk description" --type my_type --license my_license --package-version 1.2.3 --keyword my_keyword --keyword my_keyword2 --author author#email#role --author name2 --author name3#email3 --provide name/A#url1 --provide name2/B#url2 --provide name/C#url3 --suggest "name/A#description 1" --suggest "name2/B#description 2" --suggest "name/C#description 3" --support "typeA#urlA" --support "typeB#urlB" --support "typeC#urlC" --autoload-psr0 "vendor1\\Test#src1" --autoload-psr4 "\\vendor2\\Test\\#src2" --autoload-psr0 "vendor1\\Test2#src3" --autoload-dev-psr0 "vendor1\\Test#src1" --autoload-dev-psr4 "vendor2\\Test#src2" --autoload-dev-psr0 "vendor1\\Test2#src3" --require "vendor1/A#v1.3.0" --require "vendor2/B#>=2.0.0" --require "vendor1/C#~3.2" --require-dev "vendor1/A#v1.3.0" --require-dev "vendor2/B#>=2.0.0" --require-dev "vendor1/C#~3.2" --script "name1#command1" --script "name2#command1" --script "name1#command2"
    """
    Then configuration file should contains:
    """
    {
      "name": "pk_namespace2\\pk_name2",
      "type": "my_type",
      "license": "my_license",
      "version": "1.2.3",
      "description": "pk description"
    }
    """
    And configuration file should contains:
    """
    {
      "keywords": ["DEFAULT-KEYWORD1", "DEFAULT-KEYWORD2", "my_keyword", "my_keyword2"]
    }
    """
    And configuration file should contains:
    """
    {
      "authors": [
        {
          "name": "default-name1",
          "email": "default-email1",
          "role": "default-role1"
        },
        {
          "name": "default-name2",
          "email": "default-email2",
          "role": "default-role2"
        },
        {
          "name": "author",
          "email": "email",
          "role": "role"
        },
        {
          "name": "name2"
        },
        {
          "name": "name3",
          "email": "email3"
        }
      ]
    }
    """
    And configuration file should contains:
    """
    {
      "provide": {
        "package1": "default-provided-package1",
        "package2": "default-provided-package2",
        "name/A": "url1",
        "name2/B": "url2",
        "name/C": "url3"
      }
    }
    """
    And configuration file should contains:
    """
    {
      "suggest": {
        "package1": "default-suggested-package1",
        "package2": "default-suggested-package2",
        "name/A": "description 1",
        "name2/B": "description 2",
        "name/C": "description 3"
      }
    }
    """
    And configuration file should contains:
    """
    {
      "support": {
        "type1": "default-support-type1",
        "type2": "default-support-type2",
        "typeA": "urlA",
        "typeB": "urlB",
        "typeC": "urlC"
      }
    }
    """
    And configuration file should contains:
    """
    {
      "autoload": {
        "psr-0": {
         "DefaultNamespace\\DefaultSubNamespace": "default-psr0-path1",
         "DefaultNamespace\\DefaultSubNamespace2": "default-psr0-path2",
          "vendor1\\Test": "src1",
          "vendor1\\Test2": "src3"
        },
        "psr-4": {
          "\\DefaultNamespace\\DefaultSubNamespace\\": "default-psr4-path1",
          "\\DefaultNamespace\\DefaultSubNamespace2\\": "default-psr4-path2",
          "\\vendor2\\Test\\": "src2"
        }
      }
    }
    """
    And configuration file should contains:
    """
    {
      "autoload-dev": {
        "psr-0": {
          "DefaultNamespace\\DefaultSubNamespace": "default-psr0-path1",
          "DefaultNamespace\\DefaultSubNamespace2": "default-psr0-path2",
          "vendor1\\Test": "src1",
          "vendor1\\Test2": "src3"
        },
        "psr-4": {
          "\\DefaultNamespace\\DefaultSubNamespace\\": "default-psr4-path1",
          "\\DefaultNamespace\\DefaultSubNamespace2\\": "default-psr4-path2",
          "vendor2\\Test": "src2"
        }
      }
    }
    """
    And configuration file should contains:
    """
    {
      "require": {
        "requirement1": "default-required-package1",
        "vendor1/A": "v1.3.0",
        "vendor2/B": ">=2.0.0",
        "vendor1/C": "~3.2"
      }
    }
    """
    And configuration file should contains:
    """
    {
      "require-dev": {
        "requirement1": "default-required-dev-package1",
        "vendor1/A": "v1.3.0",
        "vendor2/B": ">=2.0.0",
        "vendor1/C": "~3.2"
      }
    }
    """
    And configuration file should contains:
    """
    {
      "scripts": {
        "default-script-1": [
            "default-script1-command_1",
            "default-script1-command_2"
        ],
        "default-script-2": [
            "default-script2-command_1",
            "default-script2-command_2"
        ],
        "name1": [
          "command1",
          "command2"
        ],
        "name2": [
          "command1"
        ]
      }
    }
    """

  Scenario: Full configuration file with values to update
    Given I execute composercm update with following options:
    """
    --package-name "pk_namespace2\\pk_name2" --description "pk description" --type my_type --license my_license --package-version 1.2.3  --author default-name1#email#role --author default-name2#email2 --author name3#email3 --provide package1#url1 --provide name2/B#url2 --suggest "package1#description 1" --suggest "name2/B#description 2" --support "type1#url1" --support "typeA#urlA" --autoload-psr0 "DefaultNamespace\\DefaultSubNamespace#src1" --autoload-psr4 "\\DefaultNamespace\\DefaultSubNamespace2\\#src2" --autoload-psr4 "\\vendor2\\Test\\#src4" --autoload-psr0 "vendor1\\Test2#src3" --autoload-dev-psr0 "DefaultNamespace\\DefaultSubNamespace2#src1" --autoload-dev-psr4 "\\DefaultNamespace\\DefaultSubNamespace\\#src2" --autoload-dev-psr4 "vendor2\\Test#src2" --autoload-dev-psr0 "vendor1\\Test2#src3" --require "requirement1#custom" --require "vendor2/B#>=2.0.0" --require-dev "requirement1#custom" --require-dev "vendor2/B#>=2.0.0" --script "default-script-1#command1" --script "name2#command1" --script "default-script-1#command2"
    """
    Then configuration file should contains:
    """
    {
      "name": "pk_namespace2\\pk_name2",
      "type": "my_type",
      "license": "my_license",
      "version": "1.2.3",
      "description": "pk description"
    }
    """
    And configuration file should contains:
    """
    {
      "authors": [
        {
          "name": "default-name1",
          "email": "email",
          "role": "role"
        },
        {
          "name": "default-name2",
          "email": "email2",
          "role": "default-role2"
        },
        {
          "name": "name3",
          "email": "email3"
        }
      ]
    }
    """
    And configuration file should contains:
    """
    {
      "provide": {
        "package1": "url1",
        "package2": "default-provided-package2",
        "name2/B": "url2"
      }
    }
    """
    And configuration file should contains:
    """
    {
      "suggest": {
        "package1": "description 1",
        "package2": "default-suggested-package2",
        "name2/B": "description 2"
      }
    }
    """
    And configuration file should contains:
    """
    {
      "support": {
        "type1": "url1",
        "type2": "default-support-type2",
        "typeA": "urlA"
      }
    }
    """
    And configuration file should contains:
    """
    {
      "autoload": {
        "psr-0": {
         "DefaultNamespace\\DefaultSubNamespace": "src1",
         "DefaultNamespace\\DefaultSubNamespace2": "default-psr0-path2",
         "vendor1\\Test2": "src3"
        },
        "psr-4": {
          "\\DefaultNamespace\\DefaultSubNamespace\\": "default-psr4-path1",
          "\\DefaultNamespace\\DefaultSubNamespace2\\": "src2",
          "\\vendor2\\Test\\": "src4"
        }
      }
    }
    """
    And configuration file should contains:
    """
    {
      "autoload-dev": {
        "psr-0": {
          "DefaultNamespace\\DefaultSubNamespace": "default-psr0-path1",
          "DefaultNamespace\\DefaultSubNamespace2": "src1",
          "vendor1\\Test2": "src3"
        },
        "psr-4": {
          "\\DefaultNamespace\\DefaultSubNamespace\\": "src2",
          "\\DefaultNamespace\\DefaultSubNamespace2\\": "default-psr4-path2",
          "vendor2\\Test": "src2"
        }
      }
    }
    """
    And configuration file should contains:
    """
    {
      "require": {
        "requirement1": "custom",
        "vendor2/B": ">=2.0.0"
      }
    }
    """
    And configuration file should contains:
    """
    {
      "require-dev": {
        "requirement1": "custom",
        "vendor2/B": ">=2.0.0"
      }
    }
    """
    And configuration file should contains:
    """
    {
      "scripts": {
        "default-script-1": [
            "command1",
            "command2"
        ],
        "default-script-2": [
            "default-script2-command_1",
            "default-script2-command_2"
        ],
        "name2": [
          "command1"
        ]
      }
    }
    """