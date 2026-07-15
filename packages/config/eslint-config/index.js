import js from "@eslint/js"
import { defineConfig, globalIgnores } from "eslint/config"

export const ignores = ["dist", "node_modules", "src/**/*.js"]

export const baseConfig = defineConfig([
  globalIgnores(ignores),
  js.configs.recommended,
])

export default baseConfig
