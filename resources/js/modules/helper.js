export function stripScripts(str) {

  const SCRIPT_REGEX = /<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi

  while ( SCRIPT_REGEX.test(str) ) {
    str = str.replace(SCRIPT_REGEX, '')
  }

  return str
}
