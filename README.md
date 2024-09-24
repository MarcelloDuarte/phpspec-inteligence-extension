# PhpSpec Intelligence Extension

The **PhpSpec Intelligence Extension** introduces a `next` command to PhpSpec, leveraging OpenAI's GPT models to suggest the
next specification to implement in your test-driven development workflow.

## Table of Contents

- [Introduction](#introduction)
- [Features](#features)
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
- [Examples](#examples)
- [Contributing](#contributing)
- [License](#license)

## Introduction

Test-driven development (TDD) encourages writing tests before implementing code. However, deciding what to test next
can sometimes be challenging. This extension integrates with OpenAI's API to analyze your existing specs and provide
intelligent suggestions for the next spec to write, streamlining your TDD process.

## Features

- **Automated Spec Suggestions**: Get suggestions for your next spec based on existing specifications.
- **Seamless Integration**: Adds a `next` command to PhpSpec without disrupting your workflow.
- **Configurable Parameters**: Customize API settings like model, max tokens, and temperature.

## Installation

Install the extension via Composer:

```bash
composer require --dev md/phpspec-intelligence-extension
```

## Configuration

To use the extension, you need to:

1. **Enable the Extension**: Add it to your `phpspec.yml` configuration file.
2. **Set the OpenAI API Key**: Provide your OpenAI API key securely.

### 1. Enable the Extension

Update your `phpspec.yml` file:

```yaml
extensions:
  Md\PhpSpecIntelligenceExtension\Extension:
    openai_api_key: OPENAI_API_KEY
    openai_api_model: "gpt-3.5-turbo"
    openai_api_temperature: 0.7
    openai_api_max_tokens: 256
```

### 2. Set the OpenAI API Key

For security, it's recommended to use environment variables to store your API key.

#### Using Environment Variables

Set the `OPENAI_API_KEY` environment variable in your shell or server configuration.

```bash
export OPENAI_API_KEY='your-openai-api-key'
```

#### Alternative: Configuration File (Less Secure)

You can also set the API key in the `phpspec.yml` file (not recommended for shared repositories):

```yaml
extensions:
    Md\PhpSpecIntelligenceExtension\Extension:
        openai_api_key: 'your-openai-api-key'
```

**Warning**: Avoid committing API keys to version control.

## Usage

After installation and configuration, you can use the `next` command:

```bash
vendor/bin/phpspec next
```

The command will analyze your existing specs and suggest the next one to implement.

### Command Options

- `--config`: Specify a custom configuration file.
- `--verbose`: Increase the verbosity of messages.

Example:

```bash
vendor/bin/phpspec next --config=phpspec.yml --verbose
```

## Examples

### Basic Usage

```bash
vendor/bin/phpspec next
```

**Output:**

```
Suggested example for: spec/Acme/MarkdownSpec.php:

    function it_registers_the_next_command(ServiceContainer $container)
    {
        $container->define('console.commands.next',
            Argument::type('Closure'),
            Argument::type('array')
        )->shouldBeCalled();
        $this->load($container, []);
    }

     Would you like me to generate this spec? [Y/n]
```

### Using a Custom Configuration File

```bash
vendor/bin/phpspec next --config=custom-phpspec.yml
```

### Handling API Limits

If you encounter rate limits or quota issues, the command will display an appropriate error message. Ensure your OpenAI
account has sufficient quota and consider implementing retry logic if needed.

## Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository.
2. Create a new branch for your feature or bugfix.
3. Write specs for your changes.
4. Submit a pull request with a detailed description.

### Running Tests

```bash
vendor/bin/phpspec run
```

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

---

**Note**: This extension uses the OpenAI API, which may incur costs. Monitor your usage to avoid
unexpected charges. Always handle API keys securely and follow best practices to protect sensitive
information.
