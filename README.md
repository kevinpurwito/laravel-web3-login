# Allow your users to log in with their Web3 wallet

[![Code Style](https://github.com/kevinpurwito/laravel-web3-login/actions/workflows/php-cs-fixer.yml/badge.svg?branch=main)](https://github.com/kevinpurwito/laravel-web3-login/actions/workflows/php-cs-fixer.yml)
[![Psalm](https://github.com/kevinpurwito/laravel-web3-login/actions/workflows/psalm.yml/badge.svg?branch=main)](https://github.com/kevinpurwito/laravel-web3-login/actions/workflows/psalm.yml)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/kevinpurwito/laravel-web3-login.svg?style=flat-square)](https://packagist.org/packages/kevinpurwito/laravel-web3-login)
[![Total Downloads](https://img.shields.io/packagist/dt/kevinpurwito/laravel-web3-login.svg?style=flat-square)](https://packagist.org/packages/kevinpurwito/laravel-web3-login)

Allow your users to link their Web3 wallet to their account to skip entering their login credentials.

This package is forked from the [m1guelpf/laravel-web3-login](https://github.com/m1guelpf/laravel-web3-login)
with some modifications.

## Installation

You can install the package via composer:

```bash
composer require kevinpurwito/laravel-web3-login
```

### Configuration

The `vendor:publish` command will publish a file named `web3.php` within your laravel project config
folder `config/web3.php`. Edit this file with your desired column name for the table, defaults to `wallet`.

Published Config File Contents

```php
[
    'wallet_address_column' => env('WEB3_WALLET_ADDRESS_COLUMN', 'wallet'),
];
```

Alternatively you can ignore the above publish command and add this following variables to your `.env` file.

```text
WEB3_WALLET_ADDRESS_COLUMN=wallet
```


## Usage

This package takes care of everything you need on the backend. While there are many ways of asking the user to sign a
message with their wallet, we'll be using `web3modal` and `ethers` to maximize the support for wallet providers.

To get started, you need to have the user register a new credential. You can do so by presenting them with a modal when
they log in, or by adding the option to their settings page.

```js
import axios from "axios";
import {ethers} from "ethers";
import Web3Modal from "web3modal";

const web3Modal = new Web3Modal({
	cacheProvider: true,
	providerOptions: {}, // add additional providers here, like WalletConnect, Coinbase Wallet, etc.
});

const onClick = async () => {
	const message = await axios.get("/_web3/signature").then((res) => res.data);
	const provider = await web3Modal.connect();

	provider.on("accountsChanged", () => web3Modal.clearCachedProvider());

	const web3 = new ethers.providers.Web3Provider(provider);

	axios.post("/_web3/link", {
		address: await web3.getSigner().getAddress(),
		signature: await web3.getSigner().signMessage(message),
	});
};
```

Then, on the login page, you can provide an option to log in with their wallet.

```js
import axios from "axios";
import {ethers} from "ethers";
import Web3Modal from "web3modal";

const web3Modal = new Web3Modal({
	cacheProvider: true,
	providerOptions: {}, // add additional providers here, like WalletConnect, Coinbase Wallet, etc.
});

const onClick = async () => {
	const message = await axios.get("/_web3/signature").then((res) => res.data);
	const provider = await web3Modal.connect();

	provider.on("accountsChanged", () => web3Modal.clearCachedProvider());

	const web3 = new ethers.providers.Web3Provider(provider);

	axios.post("/_web3/login", {
		address: await web3.getSigner().getAddress(),
		signature: await web3.getSigner().signMessage(message),
	});
};
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](.github/SECURITY.md) on how to report security vulnerabilities.

## Credits

- Original Author: [Miguel Piedrafita](https://github.com/m1guelpf)
- Modified by: [Kevin Purwito](https://github.com/kevinpurwito)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
