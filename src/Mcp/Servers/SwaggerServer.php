<?php

namespace Wotz\SwaggerUi\Mcp\Servers;

use Laravel\Mcp\Server;
use Wotz\SwaggerUi\Mcp\Tools\GetEndpointTool;
use Wotz\SwaggerUi\Mcp\Tools\GetGeneralInfoTool;
use Wotz\SwaggerUi\Mcp\Tools\GetRequestBodyTool;
use Wotz\SwaggerUi\Mcp\Tools\GetResponseTool;
use Wotz\SwaggerUi\Mcp\Tools\GetSchemaTool;
use Wotz\SwaggerUi\Mcp\Tools\ListEndpointsTool;
use Wotz\SwaggerUi\Mcp\Tools\ListParametersTool;
use Wotz\SwaggerUi\Mcp\Tools\ListRequestBodiesTool;
use Wotz\SwaggerUi\Mcp\Tools\ListResponsesTool;
use Wotz\SwaggerUi\Mcp\Tools\ListSchemasTool;
use Wotz\SwaggerUi\Mcp\Tools\ListSwaggerFilesTool;

class SwaggerServer extends Server
{
    protected string $name = 'Swagger/OpenAPI Server';

    protected string $version = '1.0.0';

    protected string $instructions = <<<'MARKDOWN'
        # API Documentation Server

        This MCP server provides tools for querying and understanding the API documentation without loading the entire OpenAPI specification into context.

        ## Multiple API Files

        The application may have multiple Swagger/OpenAPI specifications.
        Use the `list-swagger-files` tool first to see all available files and versions.
        All endpoint and schema tools accept optional `filename` and `version` parameters to target specific APIs.
        If the user does not specify these parameters, ask them for clarification.

        ## Available Tools

        ### File Discovery
        - **list-swagger-files**: List all available Swagger files and their versions

        ### API Information
        - **get-general-info**: Get general API information of a specific Swagger file (title, version, servers, description, security, etc.)

        ### Endpoint Discovery
        - **list-endpoints**: Browse all API endpoints in a specific Swagger file
        - **get-endpoint**: Get detailed information about a specific endpoint in a specific Swagger file

        ### Schema and Model Discovery
        - **list-schemas**: Browse all available data models/schemas in a specific Swagger file
        - **get-schema**: Get detailed information about a specific schema in a specific Swagger file

        ### Reusable Components Discovery
        - **list-parameters**: Browse all reusable parameters in a specific Swagger file
        - **list-responses**: Browse all reusable response definitions in a specific Swagger file
        - **get-response**: Get detailed information about a specific reusable response in a specific Swagger file
        - **list-request-bodies**: Browse all reusable request body definitions in a specific Swagger file
        - **get-request-body**: Get detailed information about a specific reusable request body in a specific Swagger file

        ## Tips for Best Results

        - Start with `list-swagger-files` to see available APIs to determine the correct `filename` and `version` parameters. If there are multiple options, ask the user which one to use.
        - Use the list tools to explore the API before making requests
        - Specify `filename` and `version` parameters when querying specific APIs.
    MARKDOWN;

    protected array $tools = [
        ListSwaggerFilesTool::class,

        GetGeneralInfoTool::class,

        ListParametersTool::class,

        ListResponsesTool::class,
        GetResponseTool::class,

        ListRequestBodiesTool::class,
        GetRequestBodyTool::class,

        ListEndpointsTool::class,
        GetEndpointTool::class,

        ListSchemasTool::class,
        GetSchemaTool::class,
    ];
}
