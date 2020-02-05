# Bitrix Generators

## Use

## Bitrix module

```bash
./bin/bitrix create:module vendor.module /path/to/module/folder --lang=ru --lang=en --lang=de
```

| Option | Required | Default | Description |
|---|---|---|---|
| Module name | yes | | Module name (eg. vendor.module) |
| Path | no | Current path | Module path (eg. `bitrix/modules/`) |
| Lang | no | ru | Module languages for generate lang-files |

## Bitrix simple component

```bash
./bin/bitrix create:componet vendor.component /path/to/component/folder --lang=ru --lang=en --lang=de
```

| Option | Required | Default | Description |
|---|---|---|---|
| Component name | yes | | Component name (eg. vendor.module). Dot-separated style *required* |
| Path | no | Current path | Component path (eg. `local/components/`) |
| Lang | no | ru | Component languages for generate lang-files |

Command generates module in ``/path/to/component/folder/vendor/component/`` 
