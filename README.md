# Bitrix Generators

## Use

## Bitrix module

```bash
./bin/bitrix create:module vendor.module /path/to/modules/folder --lang=ru --lang=en --lang=de
```

| Option | Required | Default | Description |
|---|---|---|---|
| Module name | yes | | Module name (eg. vendor.module) |
| Path | no | Current path | Module path (eg. `bitrix/modules/`) |
| Lang | no | ru | Module languages for generate lang-files |

Command generates module in ``/path/to/modules/folder/vendor.module/``

## Bitrix simple component

```bash
./bin/bitrix-cli create:componet vendor.component /path/to/components/folder --lang=ru --lang=en --lang=de
```

| Option | Required | Default | Description |
|---|---|---|---|
| Component name | yes | | Component name (eg. vendor.module). Dot-separated style *required* |
| Path | no | Current path | Component path (eg. `local/components/`) |
| Lang | no | ru | Component languages for generate lang-files |

Command generates module in ``/path/to/components/folder/vendor/component/``

## Bitrix template

```bash
./bin/bitrix-cli create:template general /path/to/templates/folder --lang=ru --lang=en --lang=de
```

| Option | Required | Default | Description |
|---|---|---|---|
| Template name | yes | | Template name (eg. general) |
| Path | no | Current path | Component path (eg. `local/templates/`) |

Command generates template in ``/path/to/templates/folder/general/`` 
