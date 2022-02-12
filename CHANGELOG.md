# Changelog

All notable changes to `laravel-country` will be documented in this file

## TODO
- Wards seeder from [wikipedia](https://id.wikipedia.org/wiki/Daftar_kecamatan_dan_kelurahan_di_Indonesia)

## 2.0.1 - 2021-11-15
### Fixed
- Seeder: Province codes not matching the actual code
- Seeder: Cities province_id not matching actual province_id

### Update Guide
- Run
```bash
php artisan db:seed --class=Kevinpurwito\\LaravelCountry\\Database\\Seeders\\IdProvincesSeeder
php artisan db:seed --class=Kevinpurwito\\LaravelCountry\\Database\\Seeders\\IdCitiesSeeder
```

## 2.0.0 - 2021-09-01
### Added
- Districts seeder for Indonesia
- Districts and wards migration for Indonesia

### Changed
- `order_no` column into `ordinal` column


## 1.1.1 - 2021-08-09
### Fixed
- Migration filename to ensure the order of migration is correct (countries > provinces > cities)


## 1.1.0 - 2021-08-09
### Added
- Provinces table
- Cities table
- Migration for provinces and cities in Indonesia


## 1.0.1 - 2021-08-08
### Fixed
- Laravel CountryServiceProvider


## 1.0.0 - 2021-08-07
### Added
- Country migration, seeders and model
