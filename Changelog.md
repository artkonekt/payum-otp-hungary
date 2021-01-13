# Payum OTP Hungary

## 1.1.0
##### 2021-01-13

- Fixed sandbox mode with SimplePay: SP has separate sandbox URLs (OTP used to have a single, common URL for both live/sandbox environments)
- Added `Configurator::isSandbox()` method
- Added `Api::runningInSandboxMode()` method

## 1.0.2
##### 2021-01-04

- Changed redirect URL from OTP to SimplePay
- Added Changelog (older entries have been reconstructed from git history)

## 1.0.1
##### 2016-08-24

- Fixed private key config parameter name to contain the POSID

## 1.0.0
##### 2016-03-29

- Initial release
