import globals from "globals"
import reactHooks from "eslint-plugin-react-hooks"
import tseslint from "typescript-eslint"
import { defineConfig } from "eslint/config"
import { baseConfig } from "./index.js"

export const reactLibraryConfig = defineConfig([
  baseConfig,
  {
    files: ["**/*.{ts,tsx}"],
    extends: [
      tseslint.configs.recommended,
      reactHooks.configs.flat.recommended,
    ],
    languageOptions: {
      globals: globals.browser,
    },
  },
])

export default reactLibraryConfig
