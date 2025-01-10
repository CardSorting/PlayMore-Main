# Print Order System Architecture

## Overview
The print order system follows a domain-driven design approach with clear separation of concerns. It handles the entire lifecycle of print orders from creation through payment processing and production.

## Components

### Domain Layer

#### Data Transfer Objects (DTOs)
- `PrintOrderData`: Encapsulates print order creation data
- `PaymentData`: Encapsulates payment processing data

#### Events
- `PrintOrderCreated`: Triggered when a new print order is created
- `PrintOrderStatusChanged`: Triggered when a print order's status changes

#### Notifications
- `PrintOrderStatusUpdated`: Notifies users of order status changes
- `PrintProductionFailed`: Alerts administrators of production failures

### Application Layer

#### Services
- `PrintOrderService`: Orchestrates print order operations
  - Size management
  - Order creation
  - Payment processing
  - Status management
  - Timeline tracking

#### Actions
- `CreatePrintOrderAction`: Handles print order creation logic
- `ProcessPaymentAction`: Manages Stripe payment processing

#### Event Listeners
- `SendOrderConfirmation`: Sends confirmation notifications
- `NotifyStatusChange`: Handles status change notifications
- `LogOrderActivity`: Logs order-related activities
- `InitiatePrintProduction`: Triggers print production process

### Presentation Layer

#### View Models
- `PrintOrderViewModel`: Prepares data for order views
  - Order details formatting
  - Status badge styling
  - Timeline presentation
  - Action availability logic

#### Form Requests
- `CreatePrintOrderRequest`: Validates order creation input
- `ProcessPaymentRequest`: Validates payment processing input

### Infrastructure

#### Logging
Two dedicated logging channels with JSON formatting:
- `orders`: Tracks order-related activities (90-day retention)
- `production`: Tracks production processes (90-day retention)

#### Authorization
`PrintOrderPolicy` handles authorization with granular permissions:
- View orders
- Create orders
- Cancel orders
- Request refunds
- Track shipments
- Download invoices
- Update shipping
- Contact support
- Leave reviews

## Order Statuses
1. `pending`: Initial state after creation
2. `processing`: Payment confirmed, in production
3. `shipped`: Order has been shipped
4. `completed`: Order delivered
5. `cancelled`: Order cancelled

## Event Flow
1. User creates order → `PrintOrderCreated`
   - Triggers confirmation email
   - Logs creation
   
2. Payment processed → `PrintOrderStatusChanged` (to processing)
   - Updates status
   - Notifies user
   - Initiates production
   
3. Production complete → `PrintOrderStatusChanged` (to shipped)
   - Updates status
   - Notifies user
   - Generates tracking
   
4. Delivery confirmed → `PrintOrderStatusChanged` (to completed)
   - Updates status
   - Notifies user
   - Enables review option

## Error Handling
- Payment failures handled through `PaymentException`
- Production failures trigger Slack notifications
- All errors logged with context and stack traces
- Automatic retry for production jobs (3 attempts)

## Security
- Authorization through policies
- Input validation through form requests
- Secure payment processing with Stripe
- Event-driven status updates
- Audit logging of all actions

## Monitoring
- JSON-formatted logs for easy parsing
- Slack notifications for critical issues
- Production metrics tracking
- Order status analytics
