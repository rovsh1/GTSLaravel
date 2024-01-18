@extends('mail-layout')

@section('content')

<div class="u-row-container" style="padding: 0px;background-color: transparent">
  <div class="u-row"
    style="margin: 0 auto;min-width: 320px;max-width: 600px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: transparent;">
    <div style="border-collapse: collapse;display: table;width: 100%;height: 100%;background-color: transparent;">
      <div class="u-col u-col-100" style="max-width: 320px;min-width: 600px;display: table-cell;vertical-align: top;">
        <div
          style="background-color: #ffffff;height: 100%;width: 100% !important;border-radius: 0px;-webkit-border-radius: 0px; -moz-border-radius: 0px;">
          <div class="v-col-border"
            style="box-sizing: border-box; height: 100%; padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;border-radius: 0px;-webkit-border-radius: 0px; -moz-border-radius: 0px;">
            <table class="u_content_text_11" style="font-family:arial,helvetica,sans-serif;" role="presentation"
              cellpadding="0" cellspacing="0" width="100%" border="0">
              <tbody>
                <tr>
                  <td class="v-container-padding-padding"
                    style="overflow-wrap:break-word;word-break:break-word;padding:0px 65px;font-family:arial,helvetica,sans-serif;"
                    align="left">
                    <div class="v-text-align v-line-height v-font-size"
                      style="font-size: 14px; line-height: 140%; text-align: left; word-wrap: break-word;">
                      <p style="line-height: 140%;"> </p>
                      <p style="line-height: 140%;">
                        <span style="line-height: 19.6px;">{{ __('Уважаемый :client, добрый день!', ['client' => $client->name]) }}</span>
                      </p>
                      <p style="line-height: 140%;">
                      <p style="line-height: 19.6px;">{{ __('Ваш заказ подтвержден, Ваучер доступен') }} <a href="{{ $voucher->fileUrl }}" target="_blank">по ссылке</a></p>
                      </p>
                      <p style="line-height: 140%;"> </p>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="u-row-container" style="padding: 0px;background-color: transparent">
  <div class="u-row"
    style="margin: 0 auto;min-width: 320px;max-width: 600px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: transparent;">
    <div style="border-collapse: collapse;display: table;width: 100%;height: 100%;background-color: transparent;">
      <div class="u-col u_column_4 u-col-100"
        style="max-width: 320px;min-width: 600px;display: table-cell;vertical-align: top;">
        <div
          style="background-color: #f8f8fc;height: 100%;width: 100% !important;border-radius: 0px;-webkit-border-radius: 0px; -moz-border-radius: 0px;">
          <div class="v-col-border"
            style="box-sizing: border-box; height: 100%; padding: 0px;border-top: 0px solid transparent;border-left: 50px solid #ffffff;border-right: 50px solid #ffffff;border-bottom: 20px solid #ffffff;border-radius: 0px;-webkit-border-radius: 0px; -moz-border-radius: 0px;">
            <table class="u_content_text_4" style="font-family:arial,helvetica,sans-serif;" role="presentation"
              cellpadding="0" cellspacing="0" width="100%" border="0">
              <tbody>
                <tr>
                  <td class="v-container-padding-padding"
                    style="overflow-wrap:break-word;word-break:break-word;padding:30px 65px 0px 30px;font-family:arial,helvetica,sans-serif;"
                    align="left">
                    <div class="v-text-align v-line-height v-font-size"
                      style="font-size: 13px; line-height: 140%; text-align: left; word-wrap: break-word;">
                      <p style="line-height: 140%;">
                        <span style="line-height: 18.2px;">{{ __('Номер ваучера: :id', ['id' => $voucher->number]) }}</span> 
                      </p>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
            <table class="u_content_divider_3" style="font-family:arial,helvetica,sans-serif;" role="presentation"
              cellpadding="0" cellspacing="0" width="100%" border="0">
              <tbody>
                <tr>
                  <td class="v-container-padding-padding"
                    style="overflow-wrap:break-word;word-break:break-word;padding:10px;font-family:arial,helvetica,sans-serif;"
                    align="left">
                    <table height="0px" align="center" border="0" cellpadding="0" cellspacing="0" width="93%"
                      style="border-collapse: collapse;table-layout: fixed;border-spacing: 0;mso-table-lspace: 0pt;mso-table-rspace: 0pt;vertical-align: top;border-top: 1px solid #e6e6e6;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%">
                      <tbody>
                        <tr style="vertical-align: top">
                          <td
                            style="word-break: break-word;border-collapse: collapse !important;vertical-align: top;font-size: 0px;line-height: 0px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%">
                            <span>&#160;</span>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </td>
                </tr>
              </tbody>
            </table>
            <table class="u_content_text_2" style="font-family:arial,helvetica,sans-serif;" role="presentation"
              cellpadding="0" cellspacing="0" width="100%" border="0">
              <tbody>
                <tr>
                  <td class="v-container-padding-padding"
                    style="overflow-wrap:break-word;word-break:break-word;padding:0px 65px 30px 30px;font-family:arial,helvetica,sans-serif;"
                    align="left">

                    <div class="v-text-align v-line-height v-font-size"
                      style="font-size: 13px; line-height: 140%; text-align: left; word-wrap: break-word;">
                      <p style="line-height: 140%;">
                        <span style="line-height: 18.2px;">{{ __('Дата и время создания: :createdAt', ['createdAt' => $voucher->createdAt]) }}</span> 
                      </p>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="u-row-container" style="padding: 0px;background-color: transparent">
  <div class="u-row"
    style="margin: 0 auto;min-width: 320px;max-width: 600px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: transparent;">
    <div style="border-collapse: collapse;display: table;width: 100%;height: 100%;background-color: transparent;">
      <div class="u-col u-col-100" style="max-width: 320px;min-width: 600px;display: table-cell;vertical-align: top;">
        <div
          style="background-color: #ffffff;height: 100%;width: 100% !important;border-radius: 0px;-webkit-border-radius: 0px; -moz-border-radius: 0px;">
          <div class="v-col-border"
            style="box-sizing: border-box; height: 100%; padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;border-radius: 0px;-webkit-border-radius: 0px; -moz-border-radius: 0px;">
            <table class="u_content_text_11" style="font-family:arial,helvetica,sans-serif;" role="presentation"
              cellpadding="0" cellspacing="0" width="100%" border="0">
              <tbody>
                <tr>
                  <td class="v-container-padding-padding"
                    style="overflow-wrap:break-word;word-break:break-word;padding:0px 65px;font-family:arial,helvetica,sans-serif;"
                    align="left">
                    <div class="v-text-align v-line-height v-font-size"
                      style="font-size: 14px; line-height: 140%; text-align: left; word-wrap: break-word;">
                      <p style="line-height: 140%;">
                        <span style="line-height: 19.6px;"><b>{{ __('Информация о заказе:') }}</b></span>
                      </p>
                      <p style="line-height: 140%;">&nbsp;</p>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@foreach($order->guests as $index => $guest)
    {{++$index}}. {{ $guest->fullName }}
@endforeach

Дата начала поездки: {{ $order->period->dateFrom }}
Дата завершения поездки: {{ $order->period->dateTo }}

@foreach($services as $service)
<div class="u-row-container" style="padding: 0px;background-color: transparent">
  <div class="u-row"
    style="margin: 0 auto;min-width: 320px;max-width: 600px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: transparent;">
    <div style="border-collapse: collapse;display: table;width: 100%;height: 100%;background-color: transparent;">
      <div class="u_column_4" class="u-col u-col-100"
        style="max-width: 320px;min-width: 600px;display: table-cell;vertical-align: top;">
        <div
          style="background-color: #f8f8fc;height: 100%;width: 100% !important;border-radius: 0px;-webkit-border-radius: 0px; -moz-border-radius: 0px;">
          <div class="v-col-border"
            style="box-sizing: border-box; height: 100%; padding: 0px;border-top: 0px solid transparent;border-left: 50px solid #ffffff;border-right: 50px solid #ffffff;border-bottom: 20px solid #ffffff;border-radius: 0px;-webkit-border-radius: 0px; -moz-border-radius: 0px;">
            <table class="u_content_text_3" style="font-family:arial,helvetica,sans-serif;" role="presentation"
              cellpadding="0" cellspacing="0" width="100%" border="0">
              <tbody>
                <tr>
                  <td class="v-container-padding-padding"
                    style="overflow-wrap:break-word;word-break:break-word;padding:30px 65px 10px 30px;font-family:arial,helvetica,sans-serif;"
                    align="left">
                    <div class="v-text-align v-line-height v-font-size"
                      style="font-size: 13px; font-weight: 700; line-height: 140%; text-align: left; word-wrap: break-word;">
                      <p style="line-height: 140%;">{{ $service->title }}</p>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
            <table class="u_content_divider_2" style="font-family:arial,helvetica,sans-serif;" role="presentation"
              cellpadding="0" cellspacing="0" width="100%" border="0">
              <tbody>
                <tr>
                  <td class="v-container-padding-padding"
                    style="overflow-wrap:break-word;word-break:break-word;padding:0px 10px 30px;font-family:arial,helvetica,sans-serif;"
                    align="left">
                    <table height="0px" align="center" border="0" cellpadding="0" cellspacing="0" width="93%"
                      style="border-collapse: collapse;table-layout: fixed;border-spacing: 0;mso-table-lspace: 0pt;mso-table-rspace: 0pt;vertical-align: top;border-top: 1px solid #e6e6e6;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%">
                      <tbody>
                        <tr style="vertical-align: top">
                          <td
                            style="word-break: break-word;border-collapse: collapse !important;vertical-align: top;font-size: 0px;line-height: 0px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%">
                            <span>&nbsp;</span>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </td>
                </tr>
              </tbody>
            </table>
            <table class="u_content_text_4" style="font-family:arial,helvetica,sans-serif;" role="presentation"
              cellpadding="0" cellspacing="0" width="100%" border="0">
              <tbody>
                <tr>
                  <td class="v-container-padding-padding"
                    style="overflow-wrap:break-word;word-break:break-word;padding:0px 65px 0px 30px;font-family:arial,helvetica,sans-serif;"
                    align="left">
                    <div class="v-text-align v-line-height v-font-size"
                      style="font-size: 13px; line-height: 140%; text-align: left; word-wrap: break-word;">
                      <p style="line-height: 140%;">
                        <span style="line-height: 18.2px;">{{ __('Общая сумма: :amount :currency', ['amount' => $service->price->total, 'currency' => $service->price->currency]) }}</span> 
                      </p>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
            <table class="u_content_divider_3" style="font-family:arial,helvetica,sans-serif;" role="presentation"
              cellpadding="0" cellspacing="0" width="100%" border="0">
              <tbody>
                <tr>
                  <td class="v-container-padding-padding"
                    style="overflow-wrap:break-word;word-break:break-word;padding:10px;font-family:arial,helvetica,sans-serif;"
                    align="left">
                    <table height="0px" align="center" border="0" cellpadding="0" cellspacing="0" width="93%"
                      style="border-collapse: collapse;table-layout: fixed;border-spacing: 0;mso-table-lspace: 0pt;mso-table-rspace: 0pt;vertical-align: top;border-top: 1px solid #e6e6e6;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%">
                      <tbody>
                        <tr style="vertical-align: top">
                          <td
                            style="word-break: break-word;border-collapse: collapse !important;vertical-align: top;font-size: 0px;line-height: 0px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%">
                            <span>&#160;</span>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </td>
                </tr>
              </tbody>
            </table>
            <table class="u_content_text_2" style="font-family:arial,helvetica,sans-serif;" role="presentation"
              cellpadding="0" cellspacing="0" width="100%" border="0">
              <tbody>
                <tr>
                  <td class="v-container-padding-padding"
                    style="overflow-wrap:break-word;word-break:break-word;padding:0px 65px 30px 30px;font-family:arial,helvetica,sans-serif;"
                    align="left">

                    <div class="v-text-align v-line-height v-font-size"
                      style="font-size: 13px; line-height: 140%; text-align: left; word-wrap: break-word;">
                      <p style="line-height: 140%;">
                        <span style="line-height: 18.2px;">{{ __('Статус брони: {Бронь.Статус}', []) }}</span> 
                      </p>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endforeach

<div class="u-row-container" style="padding: 0px;background-color: transparent">
  <div class="u-row"
    style="margin: 0 auto;min-width: 320px;max-width: 600px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: transparent;">
    <div style="border-collapse: collapse;display: table;width: 100%;height: 100%;background-color: transparent;">
      <div class="u-col u-col-100" style="max-width: 320px;min-width: 600px;display: table-cell;vertical-align: top;">
        <div
          style="background-color: #ffffff;height: 100%;width: 100% !important;border-radius: 0px;-webkit-border-radius: 0px; -moz-border-radius: 0px;">
          <div class="v-col-border"
            style="box-sizing: border-box; height: 100%; padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;border-radius: 0px;-webkit-border-radius: 0px; -moz-border-radius: 0px;">
            <table class="u_content_text_11" style="font-family:arial,helvetica,sans-serif;" role="presentation"
              cellpadding="0" cellspacing="0" width="100%" border="0">
              <tbody>
                <tr>
                  <td class="v-container-padding-padding"
                    style="overflow-wrap:break-word;word-break:break-word;padding:0px 65px;font-family:arial,helvetica,sans-serif;"
                    align="left">
                    <div class="v-text-align v-line-height v-font-size"
                      style="font-size: 14px; line-height: 140%; text-align: left; word-wrap: break-word;">
                      <p style="line-height: 140%;">
                        <span style="line-height: 19.6px;">{{ __('С детальной информацией об услугах можете ознакомиться во вложенном файле.') }}</span>
                      </p>
                      <p style="line-height: 140%;"> </p>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection
