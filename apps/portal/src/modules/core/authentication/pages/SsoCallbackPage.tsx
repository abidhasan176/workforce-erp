import { useEffect, useState } from "react"
import { Link, useNavigate, useParams, useSearchParams } from "react-router-dom"
import { Loader2 } from "lucide-react"

import { apiClient } from "@/lib/api"
import { setStoredToken } from "@workforce-erp/auth-client"
import { AUTH_PATHS } from "@/modules/core/authentication/navigation.ts"
import { AuthCard } from "@/modules/core/authentication/components/AuthCard.tsx"

export default function SsoCallbackPage() {
  const { provider } = useParams<{ provider: string }>()
  const [searchParams] = useSearchParams()
  const navigate = useNavigate()
  const [error, setError] = useState<string | null>(null)

  useEffect(() => {
    const code = searchParams.get("code")
    const state = searchParams.get("state")

    if (!code) {
      setError("Authorization code is missing from redirect.")
      return
    }

    let isSubscribed = true

    async function exchangeCode() {
      try {
        const response = await apiClient.post<{
          success: boolean
          token: string
          message?: string
        }>(`/api/v1/auth/sso/callback/${provider}`, { code, state })

        if (!isSubscribed) return

        if (response.success && response.token) {
          setStoredToken(response.token)
          navigate("/")
        } else {
          setError(response.message || "Failed to authenticate with SSO.")
        }
      } catch (err: any) {
        if (!isSubscribed) return
        setError(err.message || "An error occurred during SSO authentication.")
      }
    }

    exchangeCode()

    return () => {
      isSubscribed = false
    }
  }, [provider, searchParams, navigate])

  return (
    <AuthCard
      heading="Processing Authentication"
      subheading={
        error ? "Authentication failed" : "Verifying your credentials..."
      }
      footer={
        <Link
          to={AUTH_PATHS.login}
          id="back-to-login-link"
          className="font-medium text-primary underline-offset-4 hover:underline"
        >
          Back to Sign In
        </Link>
      }
    >
      <div className="flex flex-col items-center justify-center gap-4 py-8">
        {error ? (
          <p className="text-center text-sm text-destructive" role="alert">
            {error}
          </p>
        ) : (
          <>
            <Loader2 className="size-8 animate-spin text-primary" />
            <p className="text-sm text-muted-foreground">
              Please wait while we establish your session.
            </p>
          </>
        )}
      </div>
    </AuthCard>
  )
}
